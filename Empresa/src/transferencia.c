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

int client_number = 0, num_clients = 0;

int main(void)
{
	//Contar o número de clientes
	num_clients = numberOfClients();

	//Criar ramos no programa para cada cliente
	client_number = forkChildren(num_clients);
	printf("client number = %d\n", client_number);

	if (client_number == 0)
	{
		//Programa pai vai funcionar como uma pequena interface para o programa a desenvolver no futuro
		while (keep_goingG)
			;
	}
	else
	{
		//Cada programa child vai representar um cliente assim vai ser possível transferir os valores dos clientes todos ao mesmo tempo

		//Conectar à base de dados central
		CONNECT_CENTRAL_DATABASE();
		printf("Central Connection Successfull %d\n", client_number);

		//Dados das bases de dados locais
		static char *host_local = "127.0.0.1"; //ip da base de dados
		static char *user_local = "sapofree";
		static char *pass_local = "naopossodizer";
		static char *dbname_local = "cliente1";

		unsigned int port_local = 3306;
		static char *unix_socket_local = NULL;
		unsigned int flag_local = 0;

		//Conectar à base de dados local
		connG_local = mysql_init(NULL);
		if (!mysql_real_connect(connG_local, host_local, user_local, pass_local, dbname_local, port_local, unix_socket_local, flag_local))
		{
			fprintf(stderr, "Error: %s [%d]\n", mysql_error(connG_local), mysql_errno(connG_local));
			exit(1);
		}
		printf("Local Connection Successfull %d\n", client_number);

		//callback para o sinal ctrl+c para terminar ciclo infinito
		signal(SIGINT, InterceptCTRL_C);

		//Ciclo infinito
		START_CYCLE();

		printf("\nDisconnected Central %d\n", client_number);
		mysql_close(connG_central);
		printf("\nDisconnected Local %d\n", client_number);
		mysql_close(connG_local);
	}
	return 0;
}
