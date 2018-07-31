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
	static char *host_central = HOST; //ip da base de dados
	static char *user_central = USER;
	static char *pass_central = PASS;
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
 * @brief Conectar à base de dados regulação de procedimentos
 * @param void
 * @return void
 */
void CONNECT_REG_PROC_DATABASE(void)
{
	//Dados da base de dados regulação de procedimentos
	static char *host_reg_proc = HOST; //ip da base de dados
	static char *user_reg_proc = USER;
	static char *pass_reg_proc = PASS;
	static char *dbname_reg_proc = "reg_proc";

	unsigned int port_reg_proc = 3306;
	static char *unix_socket_reg_proc = NULL;
	unsigned int flag_reg_proc = 0;

	//Conectar à base de dados central
	connG_reg_proc = mysql_init(NULL);
	if (!mysql_real_connect(connG_reg_proc, host_reg_proc, user_reg_proc, pass_reg_proc, dbname_reg_proc, port_reg_proc, unix_socket_reg_proc, flag_reg_proc))
	{
		fprintf(stderr, "Error: %s [%d]\n", mysql_error(connG_reg_proc), mysql_errno(connG_reg_proc));
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
	static char *user_local = USER;
	static char *pass_local = PASS;

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
 * @brief Ciclo infinito do programa principal
 * @param void
 * @return void
 */
void START_CYCLE_PROGRAMA_PRINCIPAL(void)
{
	int count = 0;

	do
	{
		usleep(20000);
		//Variável alterada na callback ctrl+c
		
		// "Timer" feito às 3 pancadas, trocar por um timer decente 
		if(count > 1000) //Com um sleep de 0.02 segundos, 3000 é sensivelmente 1 minuto
		{
			count = 0;
			//Para terminar o subprograma no caso de ser necessário adicionar um novo cliente
			TESTAR_ATUALIZAR_PROGRAMA_PRINCIPAL();
			sleep(30);
			printf("A Reiniciar o Programa de Transferência\n");
		}else
		{
			count ++;
			//printf("%d\n",count);
		}
	} while (!atualizarG && keep_goingG);
	return;
}

/**
 * @brief Ciclo infinito dos subprogramas
 * @param void
 * @return void
 */
void START_CYCLE_SUBPROGRAMA(void)
{
	int count = 0;

	do
	{
		MOVE_VALUES();
		//intervalo entre valores obtidos, actualmente 1s
		usleep(20000);
		//Variável alterada na callback ctrl+c
		
		// "Timer" feito às 3 pancadas, trocar por um timer decente 
		if(count > 1000) //Com um sleep de 0.02 segundos, 3000 é sensivelmente 1 minuto
		{
			count = 0;
			//Para terminar o subprograma no caso de ser necessário adicionar um novo cliente
			TESTAR_ATUALIZAR_SUBPROGRAMA();
		}else
		{
			count ++;
			//printf("%d\n",count);
		}
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
//////////////////////////////////////PARTE IMPORTANTE//////////////////////////////////////////////////
/*
	Esta parte é a concatenação da string que faz de ponte entre as bds central e local.
	Ao contrário do gestor de backups que permite copiar valores de uma base de dados para a outra fazendo um INSERT de um SELECT (verificar programa) aqui não é possível dado que as bds encontram-se em sistemas diferentes.
	Assim sendo é necessário usar uma string onde é concatenada a resposta retornada da bd local e faz INSERT na bd central.
	Esta solução permite que a query gerada contenha vários tuplos aumentando as taxas de transferência. Porquê?
	-> Quando fiz o programa pela primeira vez, esta string tinha apenas a capacidade de um tuplo. Por casa linha (row) retornada era realizada a query "INSERT IGNORE registos VALUES (IDMolde, NumSensor, fase, data_hora, milissegundos, valor);" no entanto, isto permitia uma taxa de transferência de no máximo 5 tuplos/s
	-> Quando passei a inserir vários tuplos na mesma query passei a ter taxas de transferência de pelo menos 90 tuplos/s no entanto, não testei o limite máximo

	MORAL DA HISTÓRIA: o limitador principal da taxa de transferência encomtra-se aqui, aumentar a central_query_size aumenta a taxa de transferência. É preciso testar qual a taxa máxima de transferência deste programa e, se não for suficientemente alta, vai ser necessário desenvolver uma nova solução
*/
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
////////////////////////////////////////////////////////////////////////////////////////////////////////////////
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
 * @brief Função para terminar os subprogramas em caso de ser necessário atualizar o programa de transferência e adicionar novos clientes
 * @param void
 * @return void
 */
void TESTAR_ATUALIZAR_SUBPROGRAMA(void)
{

	MYSQL_RES *res;
	MYSQL_ROW row;

	unsigned int num_fields, num_rows;
	unsigned int i, j = 1;
	unsigned long *lengths;

	char query[100], str[100];

	printf("É necessário reiniciar o programa de transferência? ");

	//Ler atualizar transferência da bd reg_proc
	sprintf(query, "SELECT a_transferencia FROM atualizar");
	if (mysql_real_query(connG_reg_proc, query, (unsigned long)strlen(query)))
	{
		fprintf(stderr, "Error: %s [%d]\n", mysql_error(connG_reg_proc), mysql_errno(connG_reg_proc));
	}

	res = mysql_store_result(connG_reg_proc);
	num_fields = mysql_num_fields(res);
	while (row = mysql_fetch_row(res))
	{
		lengths = mysql_fetch_lengths(res);
		for (i = 0; i < num_fields; i++)
		{
			sprintf(str, "%.*s", (int)lengths[i],
					row[i] ? row[i] : "NULL");
			//Guarda a data e hora que vai ser usada para copiar os registos para a bd backups e depois para eliminar registos da bd central
			atualizarG = atoi(str);
		}
	}
	mysql_free_result(res);

	if(atualizarG)
	{
		printf("Sim\n");
		printf("Terminar Subprograma para Atualizar o Programa de Transferência\n");
		keep_goingG = 0;
	}else
	{
		printf("Não\n");
	}
}

/**
 * @brief Função para reiniciar o programa de transferencia, igual à função anterior só que o programa principal em vez de terminar reinicia
 * @param void
 * @return void
 */
void TESTAR_ATUALIZAR_PROGRAMA_PRINCIPAL(void)
{

	MYSQL_RES *res;
	MYSQL_ROW row;

	unsigned int num_fields, num_rows;
	unsigned int i, j = 1;
	unsigned long *lengths;

	char query[100], str[100];

	printf("É necessário reiniciar o programa de transferência? ");

	//Ler atualizar transferência da bd reg_proc
	sprintf(query, "SELECT a_transferencia FROM atualizar");
	if (mysql_real_query(connG_reg_proc, query, (unsigned long)strlen(query)))
	{
		fprintf(stderr, "Error: %s [%d]\n", mysql_error(connG_reg_proc), mysql_errno(connG_reg_proc));
	}

	res = mysql_store_result(connG_reg_proc);
	num_fields = mysql_num_fields(res);
	while (row = mysql_fetch_row(res))
	{
		lengths = mysql_fetch_lengths(res);
		for (i = 0; i < num_fields; i++)
		{
			sprintf(str, "%.*s", (int)lengths[i],
					row[i] ? row[i] : "NULL");
			//Guarda a data e hora que vai ser usada para copiar os registos para a bd backups e depois para eliminar registos da bd central
			atualizarG = atoi(str);
		}
	}
	mysql_free_result(res);

	if(atualizarG)
	{
		printf("Sim\n");
		printf("Terminar o Programa Principal para Atualizar o Programa de Transferência\n");
	}else
	{
		printf("Não\n");
	}
}

/**
 * @brief  Callback para ctrl+c, desliga ciclo infinito
 */
void InterceptCTRL_C(int a)
{
	keep_goingG = 0;
	return;
}
