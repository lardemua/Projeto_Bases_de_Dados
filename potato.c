	
#include <stdio.h>
#include <stdlib.h>

void main(void)
{
	int N;
        char *str=malloc(1000);  //string where to put the ASCII art. 1000 bytes should be enough...
        char *command="mysqldump -u sapofree -pnaopossodizer central > ~/Backups/backup_geral2.sql"; //string to put the command - complete the size.
        FILE *out = popen(command, "r");        //Execute command on shell and make output available to read
	N=fread(str, 1, 1000, out) ; str[N]='\0';  //read the command output into str
        pclose(out);     //close the stream of data
}
