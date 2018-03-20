/**
 *       @file  myf.c
 *      @brief  Contém as funções para a execução do ciclo infinito
 *
 * 
 *
 *     @author  Bruno Ramos, B.Ramos@ua.pt
 *
 *   @internal
 *     Created  09-Fev-2018
 *     Company  University of Aveiro
 *   Copyright  Copyright (c) 2018, Bruno Ramos
 *
 * =====================================================================================
 */

#include <time.h>
#include <sys/time.h>
#include <unistd.h>
#include <math.h>
#include <string.h>
#include "myf.h"

/**
 * @brief Ciclo infinito a cada ciclo ele introduz um valor na base de dados e a cada cinco ciclos ele atualiza as tabelas
 * @param void
 * @return void
 */
void START_CYCLE(void)
{
	int count = 5;

	do
	{
		if(count > 4)
		{
			CREATE_DROP_TABLES();
			count = 0;
		}
		QUERY_INSERT_INTO_TABLE();
		//intervalo entre valores obtidos, actualmente 1s
		usleep(1000000);
		count++;
	//Variável alterada na callback ctrl+c
	} while (keep_goingG);
}

/**
 * @brief Lê os parâmetros das variáveis da tabela 'parametros', cria um valor para a variável de acordo com a função e escreve os valores na tabela designada para variável
 * @param void
 * @return void
 */
void QUERY_INSERT_INTO_TABLE(void)
{
	MYSQL_RES *res;
	MYSQL_ROW row;

	unsigned int num_fields;
	unsigned int i = 0;
	unsigned long *lengths;

	int seg, miliseg;
	char query[100];
	struct timeval t1;

	float valor, O, A, f, G;

	while(strcmp(all_parametrosG[i],"") || i>25)
	{
		//Lê todos os parâmetros onde coluna onda = 'variável'
		sprintf(query,"SELECT * FROM parametros WHERE onda = '%s'",all_parametrosG[i]);

		if(mysql_query(connG, query))
		{
			fprintf(stderr, "Error: %s [%d]\n", mysql_error(connG), mysql_errno(connG));
			exit(1);
		}
		res = mysql_store_result(connG);
		while (row = mysql_fetch_row(res))
		{
			O = atof(row[1]);
			A = atof(row[2]);
			f = atof(row[3]);
			G = atof(row[4]);
		}
		printf("%s %f %f %f %f\n", all_parametrosG[i], O, A, f, G);
		mysql_free_result(res);

		//Cálculo do valor em função de horas, minutos, segundos e micro segundos
		time_t timep=time(NULL);
		struct tm *tm=localtime(&timep);
		gettimeofday(&t1, NULL);

		seg = tm->tm_hour*3600+tm->tm_min*60+tm->tm_sec;
		miliseg = t1.tv_usec/pow(10,floor(log10(abs(t1.tv_usec)))-1);//floor(log10(abs(t1.tv_usec)))-1) para retirar os dois digitos mais altos dos micro segundos porque não me lembrei que 1ms = 1000us
//https://stackoverflow.com/questions/3068397/finding-the-length-of-an-integer-in-c

		//Expressão seno em função do tempo
		valor = O+A*sin(2*PI*f*(seg+miliseg*0.01)+G);
		//Inserir na tabela 'variável' os valores (Data atual, Hora atual,'valor')
		sprintf(query,"INSERT INTO %s VALUES(CURDATE(),CURTIME(),%.02f)",all_parametrosG[i], valor);

		if(mysql_query(connG, query))
		{
			fprintf(stderr, "Error: %s [%d]\n", mysql_error(connG), mysql_errno(connG));
			exit(1);
		}
	i++;
	}
}

/**
 * @brief Cria e apaga tabelas consoante a tabela parâmetros
 * @param void
 * @return void
 */
void CREATE_DROP_TABLES(void)
{
	MYSQL_RES *res;
	MYSQL_ROW row;

	unsigned int num_fields;
	unsigned int k;
	unsigned long *lengths;

	unsigned int i = 0, n = 0, drop, create;
	char all_tables[26][20]={};

	char query[100];

	//Limpar all_parametrosG para evitar que uma variável duplique
	//Exemplo, all_parametrosG = [a b x c], se apagar x sem o ciclo fica all_parametrosG = [a b c c] porque o c andou uma casa para trás
	for (i = 0; i < 25; i += 1)
	{
		strcpy(all_parametrosG[i],"");
	}

	//Ler coluna onda da tabela parametros
	sprintf(query,"SELECT onda FROM parametros");
	if(mysql_query(connG, query))
	{
		fprintf(stderr, "Error: %s [%d]\n", mysql_error(connG), mysql_errno(connG));
		exit(1);
	}

	res = mysql_store_result(connG);
	i = 0;
	//Guarda em all_parametrosG o nome das variáveis na tabela 'parametros'
	while (row = mysql_fetch_row(res))
	{
		strcpy((char *) all_parametrosG[i], row[0]);
		i++;
	}
	mysql_free_result(res);

	//Mostrar tabelas
	sprintf(query,"SHOW TABLES");
	if(mysql_query(connG, query))
	{
		fprintf(stderr, "Error: %s [%d]\n", mysql_error(connG), mysql_errno(connG));
		exit(1);
	}

	res = mysql_store_result(connG);
	i = 0;
	//Guarda em all_tables o nome de todas as tabelas que não tenham nome igual a 'parametros'
	while (row = mysql_fetch_row(res))
	{
		if(strcmp(row[0],"parametros"))
			strcpy((char *) all_tables[i], row[0]);
		else
			i--;
		i++;
	}
	mysql_free_result(res);

	//Comparações referidas na Doxyfile
	n = 0;
	while(strcmp(all_tables[n],"") || n>25)
	{
		i = 0;
		drop = 1;
		while(strcmp(all_parametrosG[i],"") || n>25)
		{
			if(!strcmp(all_tables[n],all_parametrosG[i]))
					drop = 0;
			i++;
		}
		if(drop)
		{
			//Apagar tabela com o nome 'variável'
			printf("DROP TABLE %s\n", all_tables[n]);
			sprintf(query,"DROP TABLE %s", all_tables[n]);
			if(mysql_query(connG, query))
			{
				fprintf(stderr, "Error: %s [%d]\n", mysql_error(connG), mysql_errno(connG));
				exit(1);
			}
		}
		n++;
	}

	n = 0;
	while(strcmp(all_parametrosG[n],"") || n>25)
	{
		i = 0;
		create = 1;
		while(strcmp(all_tables[i],"") || n>25)
		{
			if(!strcmp(all_tables[i],all_parametrosG[n]))
					create = 0;
			i++;
		}
		if(create)
		{
			//Criar tabela com o nome 'variável'
			printf("CREATE TABLE %s\n", all_parametrosG[n]);
			sprintf(query,"CREATE TABLE %s (dia DATE, hora TIME, valor FLOAT(8,2))", all_parametrosG[n]);
			if(mysql_query(connG, query))
			{
				fprintf(stderr, "Error: %s [%d]\n", mysql_error(connG), mysql_errno(connG));
				exit(1);
			}
		}
		n++;
	}
}

/**
 * @brief  Callback para ctrl+c, desliga ciclo infinito
 */
void InterceptCTRL_C(int a)
{	
	keep_goingG = 0;
}
