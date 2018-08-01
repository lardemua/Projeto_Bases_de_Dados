/**
 *       @file  gestor_backups.c
 *      @brief  Gere os backups da informação do sistema
 *
 * Contém duas componentes distintas
 * Gerar backups - gera backups da informação de cada molde individualmente, juntamente com um backup geral para efeitos de recuperação do sistema
 * Modo automático atualmente desativado, refazer timers
 *
 *     @author  Bruno Ramos, B.Ramos@ua.pt
 *
 *   @internal
 *     Created  29-Mar-2018
 *     Company  University of Aveiro
 *   Copyright  Copyright (c) 2018, Bruno Ramos
 *
 * =====================================================================================
 */
#define _MAIN_C_

#include <signal.h>
#include "myf.h"

int main(void)
{
	//Conectar às bases de dados no sistema central
	CONNECT_CENTRAL_SYSTEM_DATABASES();
	printf("Central System Connection Successfull\n");

	//callback para o sinal ctrl+c para terminar ciclo infinito
	//signal(SIGINT, InterceptCTRL_C);

///////////////////////////////Modo automático removido/////////////////////////////////////////////////
//É necessário refazer o modo automático utilizando timers
//Cada componente deve ser executada com base num timer
//Gerar backups por exemplo 1x por dia
//Concatenar backups por exemplo 1x por minuto (dado que este é ativado por comando do utilizador através da bd reg_proc
	//Componente para gerar backups
	GERAR_BACKUPS();

	//Componente para conctenar backups
	CONCATENAR_BACKUPS();

	//Desconectar do sistema central
	printf("\nCentral System Disconnected\n");
	mysql_close(connG_central_system);
	mysql_library_end();

	return 0;
}
