/**
 *       @file  myf.c
 *      @brief  Contém as funções para a execução do ciclo infinito
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
		usleep(1000000);
		//Variável alterada na callback ctrl+c
	} while (keep_goingG);
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

	unsigned int central_query_size = 100000;
	char local_query[100], str[100], central_query[central_query_size], datetime_limit[22];

	//Lê todos os valores da base de dados local
	sprintf(local_query, "SELECT * FROM Registos ORDER BY data_hora, milisegundos, numero_sensor, ID_molde");
	if (mysql_real_query(connG_local, local_query, (unsigned long)strlen(local_query)))
	{
		fprintf(stderr, "Error: %s [%d]\n", mysql_error(connG_local), mysql_errno(connG_local));
	}

	res = mysql_store_result(connG_local);
	num_fields = mysql_num_fields(res);
	num_rows = mysql_num_rows(res);
	sprintf(central_query, "INSERT IGNORE Registos VALUES");
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
			//Guarda o valor do tempo para depois se apagar os valores mais antigos na base de dados local
			if (i == 2)
				sprintf(datetime_limit, "%s", str);
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
		if (j < num_rows && strlen(central_query) <= central_query_size - 100)
		{
			sprintf(str, ",");
			strcat(central_query, str);
		}
		//Se por acaso a query for demasiado grande para o tamanho da string vai divindo e submetendo até chegar ao fim da query
		else
		{
			printf("%s", central_query);
			// //Query para inserir os valores na base de dados central
			if (mysql_real_query(connG_central, central_query, (unsigned long)strlen(central_query)))
			{
				fprintf(stderr, "Error: %s [%d]\n", mysql_error(connG_central), mysql_errno(connG_central));
			}
			printf("     %d\n", (int)strlen(central_query));
			sprintf(central_query, "INSERT IGNORE Registos VALUES");
		}
		j++;
	}
	mysql_free_result(res);

	//Apaga os valores da base de dados local já movidos para a base de dados central
	sprintf(local_query, "DELETE FROM Registos WHERE data_hora < %s", datetime_limit);
	if (mysql_real_query(connG_local, local_query, (unsigned long)strlen(local_query)))
	{
		fprintf(stderr, "Error: %s [%d]\n", mysql_error(connG_local), mysql_errno(connG_local));
	}
}

/**
 * @brief  Callback para ctrl+c, desliga ciclo infinito
 */
void InterceptCTRL_C(int a)
{
	keep_goingG = 0;
}
