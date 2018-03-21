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
		COPY_VALUES();
		//intervalo entre valores obtidos, actualmente 1s
		//usleep(200000);
		//Variável alterada na callback ctrl+c
	} while (0);
}

/**
 * @brief Copiar valores de uma base de dados para a outra
 * @param void
 * @return void
 */
void COPY_VALUES(void)
{
	MYSQL_RES *res;
	MYSQL_ROW row;

	unsigned int num_fields;
	unsigned int i;
	unsigned long *lengths;

	char local_query[100], str[100], central_query[100000];

	//Lê todos os valores da tabela parametros
	sprintf(local_query, "SELECT * FROM Registos ORDER BY data_hora");
	if (mysql_real_query(connG_local, local_query, (unsigned long)strlen(local_query)))
	{
		fprintf(stderr, "Error: %s [%d]\n", mysql_error(connG_local), mysql_errno(connG_local));
	}

	res = mysql_store_result(connG_local);
	num_fields = mysql_num_fields(res);
	while (row = mysql_fetch_row(res))
	{
		sprintf(central_query, "INSERT INTO Registos VALUES(");
		lengths = mysql_fetch_lengths(res);
		for (i = 0; i < num_fields; i++)
		{
			sprintf(str, "\"%.*s\"", (int)lengths[i],
					row[i] ? row[i] : "NULL");
			strcat(central_query, str);
			if (i < num_fields - 1)
			{
				sprintf(str, ",");
				strcat(central_query, str);
			}
		}
		sprintf(str, ")");
		strcat(central_query, str);
		printf("\n");

		//Query para inserir os valores na base de dados central
		printf("%s", central_query);
		if (mysql_real_query(connG_central, central_query, (unsigned long)strlen(central_query)))
		{
			fprintf(stderr, "Error: %s [%d]\n", mysql_error(connG_central), mysql_errno(connG_central));
		}
	}
	printf("\n\n\n\n\n\n\n\n");
	mysql_free_result(res);
}

/**
 * @brief  Callback para ctrl+c, desliga ciclo infinito
 */
void InterceptCTRL_C(int a)
{
	keep_goingG = 0;
}
