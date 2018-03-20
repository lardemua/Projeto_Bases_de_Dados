#include <stdio.h>
#include <mysql/mysql.h>

#define PI 3.14159265358979323846

#ifdef _MAIN_C_
MYSQL *connG;
int keep_goingG = 1;
#else
extern MYSQL *connG;
extern int keep_goingG;
#endif

#include "prototypes.h"
