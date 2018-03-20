#My CMakeLists.txt
cmake_minimum_required (VERSION 2.8)
project ("Projeto Final Clientes")
set(PROG1 simulador)

set(SOURCES1 src/simulador.c src/myf.c)

add_executable(${PROG1} ${SOURCES1})

#Add libraries for mysql
execute_process(COMMAND mysql_config --cflags
    OUTPUT_VARIABLE MYSQL_CFLAGS)
execute_process(COMMAND mysql_config --libs
    OUTPUT_VARIABLE MYSQL_LIBS)

SET( CMAKE_C_FLAGS "${CMAKE_C_FLAGS} ${GCC_MYSQL_COMPILE_FLAGS}" )

target_link_libraries(${PROG1}  ${MYSQL_LIBS})