/**
 *       @file  simulador.c
 *      @brief  Simula as variáveis em tempo real e regista o seu valor na base de dados
 *
 * Simples conecção ao servidor MySQL baseado em: https://www.cyberciti.biz/tips/linux-unix-connect-mysql-c-api-program.html\n
O programa só termina pressionando ctrl+c.
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
#define _MAIN_C_

#include <signal.h>
#include "myf.h"

static char *host = "127.0.0.1"; //ip da base de dados
static char *user = "sapofree";
static char *pass = "naopossodizer";
static char *dbname = "cl_10004";

unsigned int port = 3306;
static char *unix_socket = NULL;
unsigned int flag = 0;

int main(void)
{
	connG = mysql_init(NULL);
	if (!mysql_real_connect(connG, host, user, pass, dbname, port, unix_socket, flag))
	{
		fprintf(stderr, "Error: %s [%d]\n", mysql_error(connG), mysql_errno(connG));
		exit(1);
	}
	printf("Connection Successfull\n");

	//callback para o sinal ctrl+c para terminar ciclo infinito
	signal(SIGINT, InterceptCTRL_C);

	//Ciclo infinito
	START_CYCLE();

	printf("\nDisconnected\n");
	mysql_close(connG);
	mysql_library_end();
	return 0;
}
