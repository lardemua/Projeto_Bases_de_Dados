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
	do
	{
		QUERY_INSERT_INTO_TABLE();
		//intervalo entre valores obtidos, actualmente 1s
		usleep(200000);
		//Variável alterada na callback ctrl+c
	} while (keep_goingG);
}

/**
 * @brief Regista os valores dos sensores na base de dados
 * @param void
 * @return void
 */
void QUERY_INSERT_INTO_TABLE(void)
{
	static int i = 0;

	int seg, miliseg, molde, sensor;
	char query[100];
	struct timeval t1;

	float valor, O, A, t, G;

	//Cálculo do valor em função de horas, minutos, segundos e micro segundos
	time_t timep = time(NULL);
	struct tm *tm = localtime(&timep);
	gettimeofday(&t1, NULL);

	seg = tm->tm_hour * 3600 + tm->tm_min * 60 + tm->tm_sec;
	miliseg = t1.tv_usec / pow(10, floor(log10(abs(t1.tv_usec))) - 1); /*floor(log10(abs(t1.tv_usec)))-1) para retirar os dois digitos mais altos dos micro segundos porque não me lembrei que 1ms = 1000us
	https://stackoverflow.com/questions/3068397/finding-the-length-of-an-integer-in-c*/

	//Termometro
	//Identificação do sensor
	molde = 20;
	sensor = 1;
	//dados da onda
	O = 500;
	A = 10;
	t = 240; //Em segundos
	G = 5;
	//Expressão seno em função do tempo
	valor = O + A * sin(2 * PI * (1 / t) * (seg + miliseg * 0.01) + G);
	//Inserir na tabela 'variável' os valores (Data atual, Hora atual,'valor')
	sprintf(query, "INSERT INTO Registos VALUES(%d,%d,NOW(),%d,%.02f)", molde, sensor, miliseg, valor);
	printf("%s\n", query);

	if (mysql_real_query(connG, query, (unsigned long)strlen(query)))
	{
		i++;
		fprintf(stderr, "Error %d: %s [%d]\n", i, mysql_error(connG), mysql_errno(connG));
		//exit(1);
	}

	//Dinamómetro 1
	//Identificação do sensor
	molde = 20;
	sensor = 2;
	//dados da onda
	O = 1500;
	A = 1500;
	t = 60; //Em segundos
	G = 0;
	//Expressão seno em função do tempo
	valor = O + A * sin(2 * PI * (1 / t) * (seg + miliseg * 0.01) + G);
	//Inserir na tabela 'variável' os valores (Data atual, Hora atual,'valor')
	sprintf(query, "INSERT INTO Registos VALUES(%d,%d,NOW(),%d,%.02f)", molde, sensor, miliseg, valor);
	printf("%s\n", query);

	if (mysql_real_query(connG, query, (unsigned long)strlen(query)))
	{
		i++;
		fprintf(stderr, "Error %d: %s [%d]\n", i, mysql_error(connG), mysql_errno(connG));
		//exit(1);
	}

	//Dinamómetro 2
	//Identificação do sensor
	molde = 20;
	sensor = 3;
	//dados da onda
	O = 1500;
	A = 1500;
	t = 60; //Em segundos
	G = 0;
	//Expressão seno em função do tempo
	valor = O + A * sin(2 * PI * (1 / t) * (seg + miliseg * 0.01) + G);
	//Inserir na tabela 'variável' os valores (Data atual, Hora atual,'valor')
	sprintf(query, "INSERT INTO Registos VALUES(%d,%d,NOW(),%d,%.02f)", molde, sensor, miliseg, valor);
	printf("%s\n", query);

	if (mysql_real_query(connG, query, (unsigned long)strlen(query)))
	{
		i++;
		fprintf(stderr, "Error %d: %s [%d]\n", i, mysql_error(connG), mysql_errno(connG));
		//exit(1);
	}

	//Dinamómetro 3
	//Identificação do sensor
	molde = 20;
	sensor = 4;
	//dados da onda
	O = 1500;
	A = 1500;
	t = 60; //Em segundos
	G = PI / 2;
	//Expressão seno em função do tempo
	valor = O + A * sin(2 * PI * (1 / t) * (seg + miliseg * 0.01) + G);
	//Inserir na tabela 'variável' os valores (Data atual, Hora atual,'valor')
	sprintf(query, "INSERT INTO Registos VALUES(%d,%d,NOW(),%d,%.02f)", molde, sensor, miliseg, valor);
	printf("%s\n", query);

	if (mysql_real_query(connG, query, (unsigned long)strlen(query)))
	{
		i++;
		fprintf(stderr, "Error %d: %s [%d]\n", i, mysql_error(connG), mysql_errno(connG));
		//exit(1);
	}

	//Dinamómetro 4
	//Identificação do sensor
	molde = 20;
	sensor = 5;
	//dados da onda
	O = 1500;
	A = 1500;
	t = 60; //Em segundos
	G = PI / 2;
	//Expressão seno em função do tempo
	valor = O + A * sin(2 * PI * (1 / t) * (seg + miliseg * 0.01) + G);
	//Inserir na tabela 'variável' os valores (Data atual, Hora atual,'valor')
	sprintf(query, "INSERT INTO Registos VALUES(%d,%d,NOW(),%d,%.02f)", molde, sensor, miliseg, valor);
	printf("%s\n", query);

	if (mysql_real_query(connG, query, (unsigned long)strlen(query)))
	{
		i++;
		fprintf(stderr, "Error %d: %s [%d]\n", i, mysql_error(connG), mysql_errno(connG));
		//exit(1);
	}
}

/**
 * @brief  Callback para ctrl+c, desliga ciclo infinito
 */
void InterceptCTRL_C(int a)
{
	keep_goingG = 0;
}
