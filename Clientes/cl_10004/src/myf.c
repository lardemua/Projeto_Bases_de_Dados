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
		sleep(60);
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

	int seg, miliseg, molde1, fase;
	char query[1000];
	struct timeval t1;

	float valor1, valor2, valor3, valor4, valor5, valor6, valor7, valor8, valor9, valor10, O, A, t, G;

	//Cálculo do valor em função de horas, minutos, segundos e micro segundos
	time_t timep = time(NULL);
	struct tm *tm = localtime(&timep);
	gettimeofday(&t1, NULL);

	seg = tm->tm_hour * 3600 + tm->tm_min * 60 + tm->tm_sec;
	miliseg = t1.tv_usec / pow(10, floor(log10(abs(t1.tv_usec))) - 1); /*pow(10, floor(log10(abs(t1.tv_usec))) - 1) para retirar os dois digitos mais altos dos micro segundos porque não me lembrei que 1ms = 1000us
	https://stackoverflow.com/questions/3068397/finding-the-length-of-an-integer-in-c*/

	i++;
	if (i < 50)
	{
		fase = 1;
	}
	else if (i >= 50 && i < 100)
	{
		fase = 2;
	}
	else if (i >= 100 && i < 150)
	{
		fase = 3;
	}
	else if (i >= 150 && i < 200)
	{
		fase = 4;
	}
	else if (i >= 200 && i < 250)
	{
		fase = 5;
	}
	else if (i >= 250)
	{
		fase = 5;
		i = 0;
	}

	//Identificação do molde
	molde1 = 9009;
	//dados da onda
	O = 500;
	A = 10.5;
	t = 240; //Em segundos
	G = 0;
	//Expressão seno em função do tempo
	valor1 = O + A * sin(2 * PI * (1 / t) * (seg + miliseg * 0.01) + G);

	//dados da onda
	O = 500;
	A = 10.5;
	t = 240; //Em segundos
	G = 0;
	//Expressão seno em função do tempo
	valor2 = O + A * sin(2 * PI * (1 / t) * (seg + miliseg * 0.01) + G);

	//dados da onda
	O = 1500;
	A = 1500;
	t = 60; //Em segundos
	G = 0;
	//Expressão seno em função do tempo
	valor3 = O + A * sin(2 * PI * (1 / t) * (seg + miliseg * 0.01) + G);

	//dados da onda
	O = 1500;
	A = 1500;
	t = 60; //Em segundos
	G = 0;
	//Expressão seno em função do tempo
	valor4 = O + A * sin(2 * PI * (1 / t) * (seg + miliseg * 0.01) + G);

	//dados da onda
	O = 1500;
	A = 500;
	t = 600; //Em segundos
	G = 0;
	//Expressão seno em função do tempo
	valor5 = O + A * sin(2 * PI * (1 / t) * (seg + miliseg * 0.01) + G);

	//dados da onda
	O = 1500;
	A = 500;
	t = 600; //Em segundos
	G = PI / 8;
	//Expressão seno em função do tempo
	valor6 = O + A * sin(2 * PI * (1 / t) * (seg + miliseg * 0.01) + G);

	//dados da onda
	O = 10;
	A = 5;
	t = 10; //Em segundos
	G = 0;
	//Expressão seno em função do tempo
	valor7 = O + A * sin(2 * PI * (1 / t) * (seg + miliseg * 0.01) + G);

	//dados da onda
	O = 10;
	A = 5;
	t = 10; //Em segundos
	G = 0;
	//Expressão seno em função do tempo
	valor8 = O + A * sin(2 * PI * (1 / t) * (seg + miliseg * 0.01) + G);

	//dados da onda
	O = 2000;
	A = 2000;
	t = 60; //Em segundos
	G = 0;
	//Expressão seno em função do tempo
	valor9 = O + A * sin(2 * PI * (1 / t) * (seg + miliseg * 0.01) + G);

	//dados da onda
	O = 2000;
	A = 2000;
	t = 60; //Em segundos
	G = 0;
	//Expressão seno em função do tempo
	valor10 = O + A * sin(2 * PI * (1 / t) * (seg + miliseg * 0.01) + G);

	//Inserir na tabela 'variável' os valores (Data atual, Hora atual,'valor')
	sprintf(query, "INSERT IGNORE registos VALUES(%d,%d,%d,NOW(),%d,%.02f),(%d,%d,%d,NOW(),%d,%.02f),(%d,%d,%d,NOW(),%d,%.02f),(%d,%d,%d,NOW(),%d,%.02f),(%d,%d,%d,NOW(),%d,%.02f),(%d,%d,%d,NOW(),%d,%.02f),(%d,%d,%d,NOW(),%d,%.02f),(%d,%d,%d,NOW(),%d,%.02f),(%d,%d,%d,NOW(),%d,%.02f),(%d,%d,%d,NOW(),%d,%.02f)", molde1, 1, fase, miliseg, valor1, molde1, 2, fase, miliseg, valor2, molde1, 3, fase, miliseg, valor3, molde1, 4, fase, miliseg, valor4, molde1, 5, fase, miliseg, valor5, molde1, 6, fase, miliseg, valor6, molde1, 7, fase, miliseg, valor7, molde1, 8, fase, miliseg, valor8, molde1, 9, fase, miliseg, valor9, molde1, 10, fase, miliseg, valor10);
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
