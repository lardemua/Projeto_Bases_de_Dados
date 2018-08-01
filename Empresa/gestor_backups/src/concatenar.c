/**
 *       @file  concatenar.c
 *      @brief  Contém as funções para a execução da componente CONCATENAR_BACKUPS() do ficheiro myf.c, as funções estão ordenadas por ordem de aparecimento no myf.c
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
#include <dirent.h> //Para ler os ficheiros num diretório
#include "myf.h"

/**
 * @brief Função para verificar se é necessário concatenar backups
 * @param void
 * @return void
 */
void TESTAR_ATUALIZAR(void)
{

	MYSQL_RES *res;
	MYSQL_ROW row;

	unsigned int num_fields, num_rows;
	unsigned int i, j = 1;
	unsigned long *lengths;

	char query[100], str[100];

	printf("É necessário concatenar backups? ");

	//Ler atualizar backups da bd reg_proc
	sprintf(query, "SELECT a_backups FROM reg_proc.atualizar");
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
			sprintf(str, "%.*s", (int)lengths[i],
					row[i] ? row[i] : "NULL");
			////Guarda atualizarG para definir o comportamento do programa
			atualizarG = atoi(str);
		}
	}
	mysql_free_result(res);

	return ;
}

/**
 * @brief Função para identificar os ficheiros do moldes e concatena-los
 * @param void
 * @return void
 */
void IDENTIFICAR_E_ARQUIVAR_MOLDES(void)
{

	//Geral
	MYSQL_RES *res;
	MYSQL_ROW row;

	unsigned int num_fields, num_rows;
	unsigned int i, j = 0;
	unsigned long *lengths;

	char query[100], str[100];

	int  IDMolde, IDMolde_ficheiro;
	char IDCliente[50], datetime_ini[25], datetime_fim[25], filename[50], command[1000];

	char *path_backups = "~/Projeto_Bases_de_Dados/Empresa/Backups/";
	char *path_backups2 = "/home/sapofree/Projeto_Bases_de_Dados/Empresa/Backups/"; //Para ler os nomes dos ficheiros, a função opendir não reconhece o ~/path
	char *path_arquivo = "~/Projeto_Bases_de_Dados/Empresa/Backups/Arquivo/";

	char buf[200];
	const char explode_char[2] = "_";
	char *token;

	//Especificas do molde
	MYSQL_RES *res_molde;
	MYSQL_ROW row_molde;

	unsigned int num_fields_molde, num_rows_data_ini, num_rows_data_fim, num_rows_clientes;
	unsigned int i_molde, j_molde = 1;
	unsigned long *lengths_molde;

	char query_molde[100], str_molde[100];

	//Ler todos os moldes que são para atualizar da bd reg_proc
	sprintf(query, "SELECT b_IDMolde FROM reg_proc.backups");
	if (mysql_real_query(connG_central_system, query, (unsigned long)strlen(query)))
	{
		fprintf(stderr, "Error: %s [%d]\n", mysql_error(connG_central_system), mysql_errno(connG_central_system));
	}

	res = mysql_store_result(connG_central_system);
	num_fields = mysql_num_fields(res);
	//Guarda o número de linhas retornadas
	num_rows = mysql_num_rows(res);
	while (row = mysql_fetch_row(res))
	{
		lengths = mysql_fetch_lengths(res);
		for (i = 0; i < num_fields; i++)
		{
			sprintf(str, "%.*s", (int)lengths[i],
					row[i] ? row[i] : "NULL");
			//Guarda o ID do molde a ser concatenado
			IDMolde = atoi(str);
			printf("\nContenar Molde %d\nA identificar e concatenar ficheiros de backup segmentados:\n", IDMolde);
		}

///////////////////////Identificar ficheiros do molde//////////////////////////////////////////////////////////////
		//Para ler o nome dos ficheiros
		//Baseado na solução: https://stackoverflow.com/questions/612097/how-can-i-get-the-list-of-files-in-a-directory-using-c-or-c
		//Para analisar o nome dos ficheiros para identificar os moldes a serem gerados usou-se a solução: https://www.tutorialspoint.com/c_standard_library/c_function_strtok.htm

		//Se o número de linhas (rows) retornadas na componente anterior forem 0, significa que não existem moldes para concatenar e por este motivo não se realiza a leitura dos ficheiros
		if(num_rows)
		{
			DIR *dir;
			struct dirent *ent;
			if((dir = opendir (path_backups2)) != NULL)
			{
				// print all the files and directories within directory
				while ((ent = readdir (dir)) != NULL)
				{					

					//printf ("%s\n", ent->d_name);
					sprintf(filename,"%s",ent->d_name);
					j = 0;

					// get the first token
					token = strtok(ent->d_name, explode_char);

					// walk through other tokens
					while( token != NULL )
					{
						if(j==2)
						{
							//printf( "%s\n", token);
							
							if(IDMolde == atoi(token))
							{
								printf ("\n%s\n", filename);

/////////////////////////////////////////////////////Carregar ficheiros de backups segmentados/////////////////////
								//Comando de terminal para carregar ficheiro
sprintf(command,"mysql -u %s -p%s backups_temp < %s\"%s\"", USER, PASS, path_backups, filename);
//printf ("%s\n", command);

								FILE *out = popen(command, "w");//Execute command on shell and make output write only
								pclose(out);//close the stream of data

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////

								//Copiar a informação da bd backups_temp para a bd backups
								COPY_BACKUPS_TEMP_TO_BACKUPS();
							}
							break;
						}else
							j++;

						token = strtok(NULL, explode_char);
					}

				}
				closedir (dir);
			}else
			{
				// could not open directory 
				perror ("Não foram encontrados ficheiros");
				return ;
			}
		}

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////

///////////////////////Verificar informação do molde para definir o nome do backup total///////////////////////////
	//Lê o cliente associado do molde a ser concatenado
	sprintf(query_molde, "SELECT m_IDCliente FROM backups.moldes WHERE m_ID = %d", IDMolde);
	if (mysql_real_query(connG_central_system, query_molde, (unsigned long)strlen(query_molde)))
	{
		fprintf(stderr, "Error: %s [%d]\n", mysql_error(connG_central_system), mysql_errno(connG_central_system));
	}

	res_molde = mysql_store_result(connG_central_system);
	num_fields_molde = mysql_num_fields(res_molde);
	//Guarda o número de linhas retornadas
	num_rows_clientes = mysql_num_rows(res_molde);
	while (row_molde = mysql_fetch_row(res_molde))
	{
		lengths_molde = mysql_fetch_lengths(res_molde);
		for (i_molde = 0; i_molde < num_fields_molde; i_molde++)
		{
			sprintf(str_molde, "\"%.*s\"", (int)lengths_molde[i_molde],
					row_molde[i_molde] ? row_molde[i_molde] : "NULL");
			//Guarda o ID do cliente
			sprintf(IDCliente, "%s", str_molde);
		}
	}
	mysql_free_result(res_molde);

	//Lê a data e hora mais antiga do molde a ser concatenado
	sprintf(query_molde, "SELECT r_data_hora FROM backups.registos ORDER BY r_data_hora LIMIT 1");
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

	//Lê a data e hora mais antiga do molde a ser concatenado
	sprintf(query_molde, "SELECT r_data_hora FROM backups.registos ORDER BY r_data_hora DESC LIMIT 1");
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


///////////////////////Gerar ficheiros mysqldump, backup total/////////////////////////////////////////////////////
	printf("\nA Gerar o Backup Total do Molde %d: ", IDMolde);

	//Se o número de linhas (rows) retornadas na componente anterior forem 0, significa que o molde a ser analisado não tem registos e por este motivo não se realiza backup individual
	if(num_rows_clientes && num_rows_data_ini && num_rows_data_fim)
	{
		//Nome do ficheiro do backup individual: backup_IDCliente_IDMolde_dataIni_dataFim.sql
		sprintf(filename,"backup_%s_%d_%s_%s.sql", IDCliente, IDMolde, datetime_ini, datetime_fim);
		//Comando de terminal mysqldump
		sprintf(command,"mysqldump -u %s -p%s backups > %s%s", USER, PASS, path_arquivo, filename);

		printf("Done\n");

		FILE *out = popen(command, "w");//Execute command on shell and make output write only
		pclose(out);//close the stream of data
	}else
		printf("Este Molde não tem Backups Segmentados\n");

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////

		//Limpar as bds backups e backups_temp para evitar dados residuais
		LIMPAR_BDS();

	}
	mysql_free_result(res);

	return ;
}

/**
 * @brief Copiar a informação da bd backups_temp para a bd backups
 * @param void
 * @return void
 */
void COPY_BACKUPS_TEMP_TO_BACKUPS(void)
{
	char query[100];

	//Copia clientes da bd backups_temp para a bd backups
	sprintf(query, "INSERT IGNORE backups.clientes SELECT * FROM backups_temp.clientes");
	if (mysql_real_query(connG_central_system, query, (unsigned long)strlen(query)))
	{
		fprintf(stderr, "Error: %s [%d]\n", mysql_error(connG_central_system), mysql_errno(connG_central_system));
	}

	//Copia moldes da bd backups_temp para a bd backups
	sprintf(query, "INSERT IGNORE backups.moldes SELECT * FROM backups_temp.moldes");
	if (mysql_real_query(connG_central_system, query, (unsigned long)strlen(query)))
	{
		fprintf(stderr, "Error: %s [%d]\n", mysql_error(connG_central_system), mysql_errno(connG_central_system));
	}

	//Copia sensores da bd backups_temp para a bd backups
	sprintf(query, "INSERT IGNORE backups.sensores SELECT * FROM backups_temp.sensores");
	if (mysql_real_query(connG_central_system, query, (unsigned long)strlen(query)))
	{
		fprintf(stderr, "Error: %s [%d]\n", mysql_error(connG_central_system), mysql_errno(connG_central_system));
	}

	//Copia registos da bd backups_temp para a bd backups
	sprintf(query, "INSERT IGNORE backups.registos SELECT * FROM backups_temp.registos");
	if (mysql_real_query(connG_central_system, query, (unsigned long)strlen(query)))
	{
		fprintf(stderr, "Error: %s [%d]\n", mysql_error(connG_central_system), mysql_errno(connG_central_system));
	}	
}

/**
 * @brief Limpar a lista dos moldes a concatenar (todos os moldes já estão concantenados neste ponto)
 * @param void
 * @return void
 */
void LIMPAR_LISTA_DE_MOLDES_A_CONCATENAR(void)
{
	char query[500];

	//Elimina os moldes a concatenar na bd reg_proc
	sprintf(query, "DELETE FROM reg_proc.backups");
	if (mysql_real_query(connG_central_system, query, (unsigned long)strlen(query)))
	{
		fprintf(stderr, "Error: %s [%d]\n", mysql_error(connG_central_system), mysql_errno(connG_central_system));
	}

	return;
}

/**
 * @brief Altera o parâmetro atualizar backups novamente para 0 da bd reg_proc
 * @param void
 * @return void
 */
void RESET_ATUALIZAR_BACKUPS(void)
{
	char query[500];

	//Altera o parâmetro atualizar backups novamente para 0 da bd reg_proc
	sprintf(query, "UPDATE reg_proc.atualizar SET a_backups = 0 WHERE a_indice = 1");
	if (mysql_real_query(connG_central_system, query, (unsigned long)strlen(query)))
	{
		fprintf(stderr, "Error: %s [%d]\n", mysql_error(connG_central_system), mysql_errno(connG_central_system));
	}

	return;
}
