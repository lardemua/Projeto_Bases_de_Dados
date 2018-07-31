/**
 *       @file  run_all.c
 *      @brief  Para executar e terminar todos os simuladores
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

#include <stdio.h>
#include <stdlib.h>
#include <string.h>

void main(void)
{
    char *command = malloc(200);
    strcpy(command, "./cl_10001/build/simulador & ./cl_10002/build/simulador & ./cl_10003/build/simulador & ./cl_10004/build/simulador & ./cl_10005/build/simulador");
    FILE *out = popen(command, "w");
    pclose(out);
    free(command);
}
