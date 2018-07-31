#My CMakeLists.txt
cmake_minimum_required (VERSION 2.8)
project ("Projeto Final Empresa")
set(PROG1 gestor_backups)

set(SOURCES1 src/gestor_backups.c src/myf.c src/gerar.c)

add_executable(${PROG1} ${SOURCES1})

#Add libraries for mysql
execute_process(COMMAND mysql_config --cflags
    OUTPUT_VARIABLE MYSQL_CFLAGS)
execute_process(COMMAND mysql_config --libs
    OUTPUT_VARIABLE MYSQL_LIBS)

SET( CMAKE_C_FLAGS "${CMAKE_C_FLAGS} ${GCC_MYSQL_COMPILE_FLAGS}" )

target_link_libraries(${PROG1}  ${MYSQL_LIBS})
