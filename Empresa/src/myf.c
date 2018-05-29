/**
 *       @file  myf.c
 *      @brief  Contém as funções para a execução do programa transferencia.c, as funções estão ordenadas por ordem de aparecimento no main
 *
 * 
 *
 *     @author  Bruno Ramos, B.Ramos@ua.pt
 *
 *   @internal
 *     Created  20-Mar-2018
 *     Company  University of Aveiro
 *   Copyright  Copyright (c) 2018, Bruno Ramos
 *
 * =====================================================================================
 */

#include <unistd.h>
#include <string.h>
#include "myf.h"

/**
 * @brief Contar o número de clientes para fazer fork n times
 * @param void
 * @return void
 */
int numberOfClients(void)
{
	MYSQL_RES *res;
	MYSQL_ROW row;

	unsigned int num_rows;

	char central_query[100];

	//Conecção temporária à base de dados central
	CONNECT_CENTRAL_DATABASE();

	//Lê os ips e os ports dos clientes na base de dados central
	sprintf(central_query, "SELECT * FROM clientes");
	if (mysql_real_query(connG_central, central_query, (unsigned long)strlen(central_query)))
	{
		fprintf(stderr, "Error: %s [%d]\n", mysql_error(connG_central), mysql_errno(connG_central));
	}

	res = mysql_store_result(connG_central);
	num_rows = mysql_num_rows(res);

	mysql_free_result(res);
	mysql_close(connG_central);
	return num_rows;
}

/**
 * @brief Função para fazer fork n vezes, baseada na solução em: https://stackoverflow.com/questions/1664787/problem-forking-fork-multiple-processes-unix
 * @param void
 * @return void
 */
int forkChildren(int nChildren)
{
	int i;
	int pid;
	for (i = 1; i <= nChildren; i++)
	{
		pid = fork();
		if (pid == -1)
		{
			/* error handling here, if needed */
			exit(1);
			return i = 0;
		}
		if (pid == 0)
		{
			printf("I am client: %d PID: %d\n", i, getpid());
			return i;
		}
	}
	return 0;
}

/**
 * @brief Conectar à base de dados central
 * @param void
 * @return void
 */
void CONNECT_CENTRAL_DATABASE(void)
{
	//Dados da base de dados central
	static char *host_central = "127.0.0.1"; //ip da base de dados
	static char *user_central = "sapofree";
	static char *pass_central = "naopossodizer";
	static char *dbname_central = "central";

	unsigned int port_central = 3306;
	static char *unix_socket_central = NULL;
	unsigned int flag_central = 0;

	//Conectar à base de dados central
	connG_central = mysql_init(NULL);
	if (!mysql_real_connect(connG_central, host_central, user_central, pass_central, dbname_central, port_central, unix_socket_central, flag_central))
	{
		fprintf(stderr, "Error: %s [%d]\n", mysql_error(connG_central), mysql_errno(connG_central));
		exit(1);
	}
	return;
}

/**
 * @brief Conectar à base de dados local
 * @param void
 * @return void
 */
void CONNECT_LOCAL_DATABASE(int client_number)
{
	MYSQL_RES *res;
	MYSQL_ROW row;

	unsigned int num_fields;
	unsigned int i, j = 1;
	unsigned long *lengths;

	char central_query[100], str[100], ID[10], IP[20], port[10];

	//Lê os IPs dos clientes na base de dados central
	sprintf(central_query, "SELECT cl_ID, cl_IP, cl_port FROM clientes ORDER BY cl_ID");
	if (mysql_real_query(connG_central, central_query, (unsigned long)strlen(central_query)))
	{
		fprintf(stderr, "Error: %s [%d]\n", mysql_error(connG_central), mysql_errno(connG_central));
	}

	res = mysql_store_result(connG_central);
	num_fields = mysql_num_fields(res);
	while (row = mysql_fetch_row(res))
	{
		lengths = mysql_fetch_lengths(res);
		for (i = 0; i < num_fields; i++)
		{
			sprintf(str, "%.*s", (int)lengths[i],
					row[i] ? row[i] : "NULL");
			//Guarda o valor do ID, IP e port
			if (j == client_number && i == 0)
				sprintf(ID, "%s", str);
			else if (j == client_number && i == 1)
				sprintf(IP, "%s", str);
			else if (j == client_number && i == 2)
				sprintf(port, "%s", str);
		}
		j++;
	}
	mysql_free_result(res);

	//Dados das bases de dados locais
	char *host_local = IP; //ip da base de dados
	static char *user_local = "transferencia";
	static char *pass_local = "transferencia1234";

	sprintf(str, "cl_%s", ID);
	char *dbname_local = str;

	unsigned int port_local = atoi(port);
	static char *unix_socket_local = NULL;
	unsigned int flag_local = 0;

	printf("IP = %s, port = %d, dbname = %s\n", host_local, port_local, dbname_local);

	//Conectar à base de dados local
	connG_local = mysql_init(NULL);
	if (!mysql_real_connect(connG_local, host_local, user_local, pass_local, dbname_local, port_local, unix_socket_local, flag_local))
	{
		fprintf(stderr, "Error: %s [%d]\n", mysql_error(connG_local), mysql_errno(connG_local));
		exit(1);
	}
	return;
}

/**
 * @brief Ciclo infinito a cada ciclo ele introduz um valor na base de dados e a cada cinco ciclos ele atualiza as tabelas
 * @param void
 * @return void
 */
void START_CYCLE(void)
{
	do
	{
		MOVE_VALUES();
		//intervalo entre valores obtidos, actualmente 1s
		usleep(20000);
		//Variável alterada na callback ctrl+c
	} while (keep_goingG);
	return;
}

/**
 * @brief Move valores de uma base de dados para a outra
 * @param void
 * @return void
 */
void MOVE_VALUES(void)
{
	MYSQL_RES *res;
	MYSQL_ROW row;

	unsigned int num_fields, num_rows;
	unsigned int i, j = 1;
	unsigned long *lengths;

	unsigned int central_query_size = 100000, tuplo_size = 100;
	char local_query[100], str[100], central_query[central_query_size], datetime_limit[22];

	//Vê a data e hora da base de dados para evitar mover valores repetidos
	sprintf(local_query, "SELECT CURRENT_TIMESTAMP");
	if (mysql_real_query(connG_local, local_query, (unsigned long)strlen(local_query)))
	{
		fprintf(stderr, "Error: %s [%d]\n", mysql_error(connG_local), mysql_errno(connG_local));
	}

	res = mysql_store_result(connG_local);
	num_fields = mysql_num_fields(res);
	while (row = mysql_fetch_row(res))
	{
		lengths = mysql_fetch_lengths(res);
		for (i = 0; i < num_fields; i++)
		{
			sprintf(str, "\"%.*s\"", (int)lengths[i],
					row[i] ? row[i] : "NULL");
			//Guarda o valor do tempo para depois se apagar os valores mais antigos na base de dados local
			sprintf(datetime_limit, "%s", str);
		}
	}
	mysql_free_result(res);

	//Lê todos os valores da base de dados local
	sprintf(local_query, "SELECT * FROM registos WHERE r_data_hora < %s ORDER BY r_data_hora, r_milissegundos, r_numSensor, r_IDMolde", datetime_limit);
	if (mysql_real_query(connG_local, local_query, (unsigned long)strlen(local_query)))
	{
		fprintf(stderr, "Error: %s [%d]\n", mysql_error(connG_local), mysql_errno(connG_local));
	}

	res = mysql_store_result(connG_local);
	num_fields = mysql_num_fields(res);
	num_rows = mysql_num_rows(res);
	sprintf(central_query, "INSERT IGNORE registos VALUES");
	while (row = mysql_fetch_row(res))
	{
		sprintf(str, "(");
		strcat(central_query, str);
		lengths = mysql_fetch_lengths(res);
		for (i = 0; i < num_fields; i++)
		{
			sprintf(str, "\"%.*s\"", (int)lengths[i],
					row[i] ? row[i] : "NULL");
			strcat(central_query, str);
			//Adiciona a "," entre os valores, não adicionando depois do último valor
			if (i < num_fields - 1)
			{
				sprintf(str, ",");
				strcat(central_query, str);
			}
		}
		sprintf(str, ")");
		strcat(central_query, str);

		//Adiciona a "," entre os tuplos, não adicionando depois do último tuplo
		if (j < num_rows && strlen(central_query) <= central_query_size - tuplo_size)
		{
			sprintf(str, ",");
			strcat(central_query, str);
		}
		//Se por acaso a query for demasiado grande para o tamanho da string vai divindo e submetendo até chegar ao fim da query
		else
		{
			printf("%s", central_query);
			//Query para inserir os valores na base de dados central
			if (mysql_real_query(connG_central, central_query, (unsigned long)strlen(central_query)))
			{
				fprintf(stderr, "Error: %s [%d]\n", mysql_error(connG_central), mysql_errno(connG_central));
			}
			printf("     %d\n", (int)strlen(central_query));
			sprintf(central_query, "INSERT IGNORE registos VALUES");
		}
		j++;
	}
	mysql_free_result(res);

	//Apaga os valores da base de dados local já movidos para a base de dados central
	sprintf(local_query, "DELETE FROM registos WHERE r_data_hora < %s", datetime_limit);
	if (mysql_real_query(connG_local, local_query, (unsigned long)strlen(local_query)))
	{
		fprintf(stderr, "Error: %s [%d]\n", mysql_error(connG_local), mysql_errno(connG_local));
	}
	return;
}

/**
 * @brief  Callback para ctrl+c, desliga ciclo infinito
 */
void InterceptCTRL_C(int a)
{
	keep_goingG = 0;
	return;
}
