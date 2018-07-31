/**
 *       @file  transferencia.c
 *      @brief  Transfere registos das bases de dados locais para a base de dados central
 *
 * Simples conecção ao servidor MySQL baseado em: https://www.cyberciti.biz/tips/linux-unix-connect-mysql-c-api-program.html\n
 * O programa só termina pressionando ctrl+c.

 * Para adicionar novos clientes ao programa é necessário reiniciar o programa manualmente com ctrl+c ou através da base de dados regulação de procedimentos
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
	//do //Ciclo para reiniciar o programa
	//{
		//Reset nas variáveis globais para quando se reinicia o programa
		keep_goingG = 1;
		atualizarG = 0;

		//Contar o número de clientes
		num_clients = numberOfClients();

		//Criar um subprograma para cada cliente
		client_number = forkChildren(num_clients);
		printf("client number = %d\n", client_number);

		if (client_number == 0)
		{
			//Conectar à base de dados regulação de procedimentos
			CONNECT_REG_PROC_DATABASE();
			printf("Regulação de Procedimentos Connection Successfull %d\n", client_number);

			//callback para o sinal ctrl+c para terminar ciclo infinito
			signal(SIGINT, InterceptCTRL_C);
			
			//Rotina do programa principal (pai), sugiro no futuro desenvolver uma interface, por exemplo em ncurses, para organizar o output de informação)
			START_CYCLE_PROGRAMA_PRINCIPAL();

			printf("\nDisconnected Regulação de Procedimentos %d\n", client_number);
			mysql_close(connG_reg_proc);
			mysql_library_end();
		}
		else
		{
			//Cada subprograma (filho) vai representar um cliente assim vai ser possível transferir os valores dos clientes todos ao mesmo tempo

			//Conectar à base de dados central
			CONNECT_CENTRAL_DATABASE();
			printf("Central Connection Successfull %d\n", client_number);

			//Conectar à base de dados regulação de procedimentos
			CONNECT_REG_PROC_DATABASE();
			printf("Regulação de Procedimentos Connection Successfull %d\n", client_number);

			//Conectar à base de dados local
			CONNECT_LOCAL_DATABASE(client_number);
			printf("Local Connection Successfull %d\n", client_number);

			//callback para o sinal ctrl+c para terminar ciclo infinito
			signal(SIGINT, InterceptCTRL_C);

			//Ciclo infinito
			START_CYCLE_SUBPROGRAMA();

			printf("\nDisconnected Central %d\n", client_number);
			mysql_close(connG_central);
			printf("\nDisconnected Regulação de Procedimentos %d\n", client_number);
			mysql_close(connG_reg_proc);
			printf("\nDisconnected Local %d\n", client_number);
			mysql_close(connG_local);
			mysql_library_end();
		}
	//}while(keep_goingG); //Ciclo para reiniciar o programa
	return 0;
}
