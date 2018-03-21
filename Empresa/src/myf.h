#include <stdio.h>
#include <mysql/mysql.h>

#define PI 3.14159265358979323846

#ifdef _MAIN_C_
MYSQL *connG_central;
MYSQL *connG_local;
int keep_goingG = 1;
#else
extern MYSQL *connG_central;
MYSQL *connG_local;
extern int keep_goingG;
#endif

#include "prototypes.h"
