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
		//QUERY_INSERT_INTO_TABLE();
		//intervalo entre valores obtidos, actualmente 1s
		//usleep(200000);
		//Variável alterada na callback ctrl+c
	} while (keep_goingG);
}

/**
 * @brief  Callback para ctrl+c, desliga ciclo infinito
 */
void InterceptCTRL_C(int a)
{
	keep_goingG = 0;
}
