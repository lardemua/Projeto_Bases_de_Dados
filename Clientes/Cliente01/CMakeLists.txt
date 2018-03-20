#My CMakeLists.txt
cmake_minimum_required (VERSION 2.8)
project ("Projeto PARI")
set(PROG1 api)
set(PROG2 simulador)

set(SOURCES1 src/api/api.c src/api/window_main.c src/api/window_connect.c src/api/window_alarms.c src/api/window_edit.c src/api/window_about.c)
set(SOURCES2 src/simulador/simulador.c src/simulador/myf.c)

add_executable(${PROG1} ${SOURCES1})
add_executable(${PROG2} ${SOURCES2})

#Add libraries for mysql
execute_process(COMMAND mysql_config --cflags
    OUTPUT_VARIABLE MYSQL_CFLAGS)
execute_process(COMMAND mysql_config --libs
    OUTPUT_VARIABLE MYSQL_LIBS)

SET( CMAKE_C_FLAGS "${CMAKE_C_FLAGS} ${GCC_MYSQL_COMPILE_FLAGS}" )

#-----------------------------------------------
#The recommended way to find packages automatically using PkgConfig
#See more on http://www.cmake.org/Wiki/CMake:How_To_Find_Libraries
find_package(PkgConfig REQUIRED)  #necessary for next command to work
pkg_check_modules(GTK REQUIRED gtk+-3.0)

include_directories(${GTK_INCLUDE_DIRS})
link_directories(${GTK_LIBRARY_DIRS})

target_link_libraries(${PROG1} ${GTK_LIBRARIES} ${MYSQL_LIBS})
target_link_libraries(${PROG2} ${GTK_LIBRARIES} ${MYSQL_LIBS})


#-----------------------------------------------------
# --- add custom mtargets (and dependencies)
add_custom_target( cpfiles
		COMMAND mkdir -p "${PROJECT_BINARY_DIR}/Backup"
		COMMAND mkdir -p "${PROJECT_BINARY_DIR}/Backup/src"
		COMMAND mkdir -p "${PROJECT_BINARY_DIR}/Backup/src/api"
		COMMAND mkdir -p "${PROJECT_BINARY_DIR}/Backup/src/simulador"
                COMMAND cp "${PROJECT_SOURCE_DIR}/src/api/*" "${PROJECT_BINARY_DIR}/Backup/src/api"
                COMMAND cp "${PROJECT_SOURCE_DIR}/src/simulador/*" "${PROJECT_BINARY_DIR}/Backup/src/simulador"
		COMMAND cp "${PROJECT_SOURCE_DIR}/CMakeLists.txt" "${PROJECT_BINARY_DIR}/Backup"
                COMMAND cp "${PROJECT_SOURCE_DIR}/src/api/*.glade" "${PROJECT_BINARY_DIR}"
                COMMAND cp "${PROJECT_SOURCE_DIR}/src/api/*.PNG" "${PROJECT_BINARY_DIR}"
                 )

#Add dependencies
add_dependencies(${PROG1} cpfiles)


# The project version number. Used in documentation generation and similar.
# Optional but recommended
set(VERSION_MAJOR   1   CACHE STRING "Project major version number.")
set(VERSION_MINOR   4   CACHE STRING "Project minor version number.")
set(VERSION_PATCH   1   CACHE STRING "Project patch version number.")
mark_as_advanced(VERSION_MAJOR VERSION_MINOR VERSION_PATCH)

#-- add a target to generate API documentation with Doxygen
find_package(Doxygen)
option(BUILD_DOCUMENTATION "Create and install doc (requires Doxygen)" ${DOXYGEN_FOUND})
if(BUILD_DOCUMENTATION)
    if(NOT DOXYGEN_FOUND)
        message(FATAL_ERROR "Doxygen is needed to build the documentation.")
    endif()
    set(doxyfile_in "${CMAKE_CURRENT_SOURCE_DIR}/doc/Doxyfile.in" )
    set(doxyfile "${CMAKE_CURRENT_BINARY_DIR}/Doxyfile")
    set(doxy_main_page "${CMAKE_CURRENT_SOURCE_DIR}/doc/mainpage.dox")
    configure_file(${doxyfile_in} ${doxyfile} @ONLY)
    add_custom_target(doc
        COMMAND ${DOXYGEN_EXECUTABLE}
        WORKING_DIRECTORY "${CMAKE_CURRENT_BINARY_DIR}"
        COMMENT "Generating API documentation with Doxygen"
        VERBATIM)
endif()
#code inspired on: http://www.tty1.net/blog/2014/cmake-doxygen_en.html
#---------------------------------------------------
