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

//Dados da base de dados central
static char *host_central = "127.0.0.1"; //ip da base de dados
static char *user_central = "sapofree";
static char *pass_central = "naopossodizer";
static char *dbname_central = "central";

unsigned int port_central = 3306;
static char *unix_socket_central = NULL;
unsigned int flag_central = 0;

//Dados das bases de dados locais
static char *host_local = "127.0.0.1"; //ip da base de dados
static char *user_local = "sapofree";
static char *pass_local = "naopossodizer";
static char *dbname_local = "cliente1";

unsigned int port_local = 3306;
static char *unix_socket_local = NULL;
unsigned int flag_local = 0;

int main(void)
{
	//Conectar à base de dados central
	connG_central = mysql_init(NULL);
	if (!mysql_real_connect(connG_central, host_central, user_central, pass_central, dbname_central, port_central, unix_socket_central, flag_central))
	{
		fprintf(stderr, "Error: %s [%d]\n", mysql_error(connG_central), mysql_errno(connG_central));
		exit(1);
	}
	printf("Central Connection Successfull\n");

	//Conectar à base de dados local
	connG_local = mysql_init(NULL);
	if (!mysql_real_connect(connG_local, host_local, user_local, pass_local, dbname_local, port_local, unix_socket_local, flag_local))
	{
		fprintf(stderr, "Error: %s [%d]\n", mysql_error(connG_local), mysql_errno(connG_local));
		exit(1);
	}
	printf("Local Connection Successfull\n");

	//callback para o sinal ctrl+c para terminar ciclo infinito
	signal(SIGINT, InterceptCTRL_C);

	//Ciclo infinito
	START_CYCLE();

	printf("\nDisconnected Central\n");
	mysql_close(connG_central);
	printf("\nDisconnected Local\n");
	mysql_close(connG_local);
	return 0;
}
