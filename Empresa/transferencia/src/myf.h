#include <stdio.h>
#include <mysql/mysql.h>

#define PI 3.14159265358979323846
#define HOST "localhost"
#define USER "transferencia"
#define PASS "transferencia1234"

#ifdef _MAIN_C_
MYSQL *connG_central;
MYSQL *connG_reg_proc;
MYSQL *connG_local;
int keep_goingG = 1;
int atualizarG = 0;
#else
extern MYSQL *connG_central;
extern MYSQL *connG_reg_proc;
extern MYSQL *connG_local;
extern int keep_goingG;
extern int atualizarG;
#endif

#include "prototypes.h"
