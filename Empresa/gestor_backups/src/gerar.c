/**
 *       @file  gerar.c
 *      @brief  Contém as funções para a execução da componente GERAR_BACKUPS() do ficheiro myf.c, as funções estão ordenadas por ordem de aparecimento no myf.c
 *
 * 
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

#include <unistd.h>
#include <string.h>
#include "myf.h"

/**
 * @brief Ler data e hora do sistema para evitar perda de registos
 * @param void
 * @return void
 */
void TIMESTAMP(void)
{
	MYSQL_RES *res;
	MYSQL_ROW row;

	unsigned int num_fields, num_rows;
	unsigned int i, j = 1;
	unsigned long *lengths;

	char query[100], str[100];

	//Ler data e hora do sistema para evitar perda de registos
	sprintf(query, "SELECT CURRENT_TIMESTAMP");
	if (mysql_real_query(connG_central_system, query, (unsigned long)strlen(query)))
	{
		fprintf(stderr, "Error: %s [%d]\n", mysql_error(connG_central_system), mysql_errno(connG_central_system));
	}

	res = mysql_store_result(connG_central_system);
	num_fields = mysql_num_fields(res);
	while (row = mysql_fetch_row(res))
	{
		lengths = mysql_fetch_lengths(res);
		for (i = 0; i < num_fields; i++)
		{
			sprintf(str, "\"%.*s\"", (int)lengths[i],
					row[i] ? row[i] : "NULL");
			//Guarda a data e hora que vai ser usada para copiar os registos para a bd backups e depois para eliminar registos da bd central
			sprintf(datetime_limitG, "%s", str);
		}
	}
	mysql_free_result(res);

	return ;
}

/**
 * @brief Copia registos para a bd backups antes da data e hora lida anteriormente
 * @param void
 * @return void
 */
void COPY_CENTRAL_TO_BACKUPS(void)
{
	char query[100];

	//Copia clientes da bd central para a bd backups
	sprintf(query, "INSERT IGNORE backups.clientes SELECT * FROM central.clientes");
	if (mysql_real_query(connG_central_system, query, (unsigned long)strlen(query)))
	{
		fprintf(stderr, "Error: %s [%d]\n", mysql_error(connG_central_system), mysql_errno(connG_central_system));
	}

	//Copia moldes da bd central para a bd backups
	sprintf(query, "INSERT IGNORE backups.moldes SELECT * FROM central.moldes");
	if (mysql_real_query(connG_central_system, query, (unsigned long)strlen(query)))
	{
		fprintf(stderr, "Error: %s [%d]\n", mysql_error(connG_central_system), mysql_errno(connG_central_system));
	}

	//Copia sensores da bd central para a bd backups
	sprintf(query, "INSERT IGNORE backups.sensores SELECT * FROM central.sensores");
	if (mysql_real_query(connG_central_system, query, (unsigned long)strlen(query)))
	{
		fprintf(stderr, "Error: %s [%d]\n", mysql_error(connG_central_system), mysql_errno(connG_central_system));
	}

	//Copia registos anteriores à data e hora lidas anteriormente da bd central para a bd backups
	sprintf(query, "INSERT IGNORE backups.registos SELECT * FROM central.registos WHERE r_data_hora < %s", datetime_limitG);
	if (mysql_real_query(connG_central_system, query, (unsigned long)strlen(query)))
	{
		fprintf(stderr, "Error: %s [%d]\n", mysql_error(connG_central_system), mysql_errno(connG_central_system));
	}

	return ;
}

/**
 * @brief Gera um backup individual de cada molde, se este tiver gerado registos
 * @param void
 * @return void
 */
void FILTRAR_E_GERAR_BACKUPS_INDIVIDUAIS(void)
{
	//Geral
	MYSQL_RES *res;
	MYSQL_ROW row;

	unsigned int num_fields, num_rows;
	unsigned int i, j = 1;
	unsigned long *lengths;

	char query[100], str[100];
	char IDCliente[50], IDMolde[50], datetime_ini[25], datetime_fim[25], filename[50], command[500];
	char *path = "~/Projeto_Bases_de_Dados/Empresa/Backups/";

	//Especificas do molde
	MYSQL_RES *res_molde;
	MYSQL_ROW row_molde;

	unsigned int num_fields_molde, num_rows_data_ini, num_rows_data_fim;
	unsigned int i_molde, j_molde = 1;
	unsigned long *lengths_molde;

	char query_molde[100], str_molde[100];

	//Lê a informação dos clientes e moldes
	sprintf(query, "SELECT m_IDCliente, m_ID FROM backups.moldes");
	if (mysql_real_query(connG_central_system, query, (unsigned long)strlen(query)))
	{
		fprintf(stderr, "Error: %s [%d]\n", mysql_error(connG_central_system), mysql_errno(connG_central_system));
	}

	res = mysql_store_result(connG_central_system);
	num_fields = mysql_num_fields(res);
	while (row = mysql_fetch_row(res))
	{
	//Cada linha (row) retornada corresponde a um molde distinto e assim os seguintes comandos correm em loop até serem analisados todos os moldes
		lengths = mysql_fetch_lengths(res);
		for (i = 0; i < num_fields; i++)
		{
			sprintf(str, "\"%.*s\"", (int)lengths[i],
					row[i] ? row[i] : "NULL");
			
			//Guarda o valor do ID do cliente e do molde a ser analisado
			if (i == 0)
				sprintf(IDCliente, "%s", str);
			else if (i == 1)
				sprintf(IDMolde, "%s", str);
		}

///////////////////////Copiar Backups de cada molde individual para backups temporária//////////////////////////////
	//Copia clientes onde o cliente é igual a IDCliente da bd backups para a bd backups_temp
	sprintf(query, "INSERT IGNORE backups_temp.clientes SELECT * FROM backups.clientes WHERE cl_ID = %s", IDCliente);
	if (mysql_real_query(connG_central_system, query, (unsigned long)strlen(query)))
	{
		fprintf(stderr, "Error: %s [%d]\n", mysql_error(connG_central_system), mysql_errno(connG_central_system));
	}

	//Copia moldes onde o molde é igual a IDMolde da bd backups para a bd backups_temp
	sprintf(query, "INSERT IGNORE backups_temp.moldes SELECT * FROM backups.moldes WHERE m_ID = %s", IDMolde);
	if (mysql_real_query(connG_central_system, query, (unsigned long)strlen(query)))
	{
		fprintf(stderr, "Error: %s [%d]\n", mysql_error(connG_central_system), mysql_errno(connG_central_system));
	}

	//Copia sensores onde o molde associado é igual a IDMolde da bd backups para a bd backups_temp
	sprintf(query, "INSERT IGNORE backups_temp.sensores SELECT * FROM backups.sensores WHERE s_IDMolde = %s", IDMolde);
	if (mysql_real_query(connG_central_system, query, (unsigned long)strlen(query)))
	{
		fprintf(stderr, "Error: %s [%d]\n", mysql_error(connG_central_system), mysql_errno(connG_central_system));
	}

	//Copia registos onde o molde associado é igual a IDMolde da bd backups para a bd backups_temp
	sprintf(query, "INSERT IGNORE backups_temp.registos SELECT * FROM backups.registos WHERE r_IDMolde = %s", IDMolde);
	if (mysql_real_query(connG_central_system, query, (unsigned long)strlen(query)))
	{
		fprintf(stderr, "Error: %s [%d]\n", mysql_error(connG_central_system), mysql_errno(connG_central_system));
	}	
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////


///////////////////////Verificar data e hora mais antiga e mais recente de cada molde//////////////////////////////
	sprintf(query_molde, "SELECT r_data_hora FROM backups_temp.registos ORDER BY r_data_hora LIMIT 1");
	if (mysql_real_query(connG_central_system, query_molde, (unsigned long)strlen(query_molde)))
	{
		fprintf(stderr, "Error: %s [%d]\n", mysql_error(connG_central_system), mysql_errno(connG_central_system));
	}

	res_molde = mysql_store_result(connG_central_system);
	num_fields_molde = mysql_num_fields(res_molde);
	//Guarda o número de linhas retornadas
	num_rows_data_ini = mysql_num_rows(res_molde);
	while (row_molde = mysql_fetch_row(res_molde))
	{
		lengths_molde = mysql_fetch_lengths(res_molde);
		for (i_molde = 0; i_molde < num_fields_molde; i_molde++)
		{
			sprintf(str_molde, "\"%.*s\"", (int)lengths_molde[i_molde],
					row_molde[i_molde] ? row_molde[i_molde] : "NULL");
			//Guarda data e hora mais antiga
			sprintf(datetime_ini, "%s", str_molde);
		}
	}
	mysql_free_result(res_molde);


	sprintf(query_molde, "SELECT r_data_hora FROM backups_temp.registos ORDER BY r_data_hora DESC LIMIT 1");
	if (mysql_real_query(connG_central_system, query_molde, (unsigned long)strlen(query_molde)))
	{
		fprintf(stderr, "Error: %s [%d]\n", mysql_error(connG_central_system), mysql_errno(connG_central_system));
	}

	res_molde = mysql_store_result(connG_central_system);
	num_fields_molde = mysql_num_fields(res_molde);
	//Guarda o número de linhas retornadas
	num_rows_data_fim = mysql_num_rows(res_molde);
	while (row_molde = mysql_fetch_row(res_molde))
	{
		lengths_molde = mysql_fetch_lengths(res_molde);
		for (i_molde = 0; i_molde < num_fields_molde; i_molde++)
		{
			sprintf(str_molde, "\"%.*s\"", (int)lengths_molde[i_molde],
					row_molde[i_molde] ? row_molde[i_molde] : "NULL");
			//Guarda data e hora mais recente
			sprintf(datetime_fim, "%s", str_molde);
		}
	}
	mysql_free_result(res_molde);

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////


///////////////////////Gerar ficheiros mysqldump///////////////////////////////////////////////////////////////////
	printf("\nMolde %s tem registos? ", IDMolde);

	//Se o número de linhas (rows) retornadas na componente anterior forem 0, significa que o molde a ser analisado não tem registos e por este motivo não se realiza backup individual
	if(num_rows_data_ini && num_rows_data_fim)
	{
		printf("Sim\n");
		//Nome do ficheiro do backup individual: backup_IDCliente_IDMolde_dataIni_dataFim.sql
		sprintf(filename,"backup_%s_%s_%s_%s.sql", IDCliente, IDMolde, datetime_ini, datetime_fim);
		//Comando de terminal mysqldump
		sprintf(command,"mysqldump -u %s -p%s backups_temp > %s%s", USER, PASS, path, filename);

		printf("%s\n",filename);

		FILE *out = popen(command, "w");//Execute command on shell and make output write only
		pclose(out);//close the stream of data
	}else
		printf("Não\n");

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////


///////////////////////Limpar das BDs de backups a informação do molde/////////////////////////////////////////////
	//Apaga moldes da bd backups_temp
	sprintf(query_molde, "DELETE FROM backups_temp.moldes");
	if (mysql_real_query(connG_central_system, query_molde, (unsigned long)strlen(query_molde)))
	{
		fprintf(stderr, "Error: %s [%d]\n", mysql_error(connG_central_system), mysql_errno(connG_central_system));
	}

	//Apaga clientes da bd backups_temp
	sprintf(query_molde, "DELETE FROM backups_temp.clientes");
	if (mysql_real_query(connG_central_system, query_molde, (unsigned long)strlen(query_molde)))
	{
		fprintf(stderr, "Error: %s [%d]\n", mysql_error(connG_central_system), mysql_errno(connG_central_system));
	}

	//Apaga registos onde o molde associado é igual a IDMolde da bd backups
	sprintf(query_molde, "DELETE FROM backups.registos WHERE r_IDMolde = %s", IDMolde);
	if (mysql_real_query(connG_central_system, query_molde, (unsigned long)strlen(query_molde)))
	{
		fprintf(stderr, "Error: %s [%d]\n", mysql_error(connG_central_system), mysql_errno(connG_central_system));
	}
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	}
	mysql_free_result(res);

	return;
}

/**
 * @brief Gera o backup geral para efeitos de recuperação de sistema
 * @param void
 * @return void
 */
void GERAR_BACKUP_GERAL(void)
{
	char filename[50], command[500];
	char *path = "~/Projeto_Bases_de_Dados/Empresa/Backups/";

	//Gera backup geral
	sprintf(filename,"backup_geral.sql");
	sprintf(command,"mysqldump -u %s -p%s backups_temp > %s%s", USER, PASS, path, filename);

	printf("\n%s\n",filename);

        FILE *out = popen(command, "w");//Execute command on shell and make output write only
        pclose(out);//close the stream of data	
	
	return;
}

/**
 * @brief Limpa a bd central dos registos já armazenados em ficheiros
 * @param void
 * @return void
 */
void LIMPAR_CENTRAL(void)
{
	char query[100];

	//Apaga registos onde o molde associado é igual a IDMolde da bd central
	sprintf(query, "DELETE FROM central.registos WHERE r_data_hora < %s", datetime_limitG);
	if (mysql_real_query(connG_central_system, query, (unsigned long)strlen(query)))
	{
		fprintf(stderr, "Error: %s [%d]\n", mysql_error(connG_central_system), mysql_errno(connG_central_system));
	}	
	
	return;
}
