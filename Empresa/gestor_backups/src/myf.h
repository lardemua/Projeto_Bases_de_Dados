#include <stdio.h>
#include <mysql/mysql.h>

#define PI 3.14159265358979323846
#define HOST "localhost"
#define USER "backupmanager"
#define PASS "backup1234"

#ifdef _MAIN_C_
MYSQL *connG_central_system;
int keep_goingG = 1;
int atualizarG = 0;
char datetime_limitG[22];
#else
extern MYSQL *connG_central_system;
extern int keep_goingG;
extern int atualizarG;
extern char datetime_limitG[22];
#endif

#include "prototypes.h"
