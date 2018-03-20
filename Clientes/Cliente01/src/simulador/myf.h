#include <stdio.h>
#include <mysql/mysql.h>

#define PI 3.14159265358979323846

#ifdef _MAIN_C_
	MYSQL *connG;
	int keep_goingG = 1;
	char all_parametrosG[26][20]={};
#else
	extern MYSQL *connG;
	extern int keep_goingG;
	extern char all_parametrosG[26][20];
#endif

#include "prototypes.h"
