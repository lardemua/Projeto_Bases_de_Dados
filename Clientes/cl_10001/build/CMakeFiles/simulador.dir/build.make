# CMAKE generated file: DO NOT EDIT!
# Generated by "Unix Makefiles" Generator, CMake Version 3.5

# Delete rule output on recipe failure.
.DELETE_ON_ERROR:


#=============================================================================
# Special targets provided by cmake.

# Disable implicit rules so canonical targets will work.
.SUFFIXES:


# Remove some rules from gmake that .SUFFIXES does not remove.
SUFFIXES =

.SUFFIXES: .hpux_make_needs_suffix_list


# Suppress display of executed commands.
$(VERBOSE).SILENT:


# A target that is always out of date.
cmake_force:

.PHONY : cmake_force

#=============================================================================
# Set environment variables for the build.

# The shell in which to execute make rules.
SHELL = /bin/sh

# The CMake executable.
CMAKE_COMMAND = /usr/bin/cmake

# The command to remove a file.
RM = /usr/bin/cmake -E remove -f

# Escaping for special characters.
EQUALS = =

# The top-level source directory on which CMake was run.
CMAKE_SOURCE_DIR = /home/sapofree/Projeto_Bases_de_Dados/Clientes/cl_10001

# The top-level build directory on which CMake was run.
CMAKE_BINARY_DIR = /home/sapofree/Projeto_Bases_de_Dados/Clientes/cl_10001/build

# Include any dependencies generated for this target.
include CMakeFiles/simulador.dir/depend.make

# Include the progress variables for this target.
include CMakeFiles/simulador.dir/progress.make

# Include the compile flags for this target's objects.
include CMakeFiles/simulador.dir/flags.make

CMakeFiles/simulador.dir/src/simulador.c.o: CMakeFiles/simulador.dir/flags.make
CMakeFiles/simulador.dir/src/simulador.c.o: ../src/simulador.c
	@$(CMAKE_COMMAND) -E cmake_echo_color --switch=$(COLOR) --green --progress-dir=/home/sapofree/Projeto_Bases_de_Dados/Clientes/cl_10001/build/CMakeFiles --progress-num=$(CMAKE_PROGRESS_1) "Building C object CMakeFiles/simulador.dir/src/simulador.c.o"
	/usr/bin/cc  $(C_DEFINES) $(C_INCLUDES) $(C_FLAGS) -o CMakeFiles/simulador.dir/src/simulador.c.o   -c /home/sapofree/Projeto_Bases_de_Dados/Clientes/cl_10001/src/simulador.c

CMakeFiles/simulador.dir/src/simulador.c.i: cmake_force
	@$(CMAKE_COMMAND) -E cmake_echo_color --switch=$(COLOR) --green "Preprocessing C source to CMakeFiles/simulador.dir/src/simulador.c.i"
	/usr/bin/cc  $(C_DEFINES) $(C_INCLUDES) $(C_FLAGS) -E /home/sapofree/Projeto_Bases_de_Dados/Clientes/cl_10001/src/simulador.c > CMakeFiles/simulador.dir/src/simulador.c.i

CMakeFiles/simulador.dir/src/simulador.c.s: cmake_force
	@$(CMAKE_COMMAND) -E cmake_echo_color --switch=$(COLOR) --green "Compiling C source to assembly CMakeFiles/simulador.dir/src/simulador.c.s"
	/usr/bin/cc  $(C_DEFINES) $(C_INCLUDES) $(C_FLAGS) -S /home/sapofree/Projeto_Bases_de_Dados/Clientes/cl_10001/src/simulador.c -o CMakeFiles/simulador.dir/src/simulador.c.s

CMakeFiles/simulador.dir/src/simulador.c.o.requires:

.PHONY : CMakeFiles/simulador.dir/src/simulador.c.o.requires

CMakeFiles/simulador.dir/src/simulador.c.o.provides: CMakeFiles/simulador.dir/src/simulador.c.o.requires
	$(MAKE) -f CMakeFiles/simulador.dir/build.make CMakeFiles/simulador.dir/src/simulador.c.o.provides.build
.PHONY : CMakeFiles/simulador.dir/src/simulador.c.o.provides

CMakeFiles/simulador.dir/src/simulador.c.o.provides.build: CMakeFiles/simulador.dir/src/simulador.c.o


CMakeFiles/simulador.dir/src/myf.c.o: CMakeFiles/simulador.dir/flags.make
CMakeFiles/simulador.dir/src/myf.c.o: ../src/myf.c
	@$(CMAKE_COMMAND) -E cmake_echo_color --switch=$(COLOR) --green --progress-dir=/home/sapofree/Projeto_Bases_de_Dados/Clientes/cl_10001/build/CMakeFiles --progress-num=$(CMAKE_PROGRESS_2) "Building C object CMakeFiles/simulador.dir/src/myf.c.o"
	/usr/bin/cc  $(C_DEFINES) $(C_INCLUDES) $(C_FLAGS) -o CMakeFiles/simulador.dir/src/myf.c.o   -c /home/sapofree/Projeto_Bases_de_Dados/Clientes/cl_10001/src/myf.c

CMakeFiles/simulador.dir/src/myf.c.i: cmake_force
	@$(CMAKE_COMMAND) -E cmake_echo_color --switch=$(COLOR) --green "Preprocessing C source to CMakeFiles/simulador.dir/src/myf.c.i"
	/usr/bin/cc  $(C_DEFINES) $(C_INCLUDES) $(C_FLAGS) -E /home/sapofree/Projeto_Bases_de_Dados/Clientes/cl_10001/src/myf.c > CMakeFiles/simulador.dir/src/myf.c.i

CMakeFiles/simulador.dir/src/myf.c.s: cmake_force
	@$(CMAKE_COMMAND) -E cmake_echo_color --switch=$(COLOR) --green "Compiling C source to assembly CMakeFiles/simulador.dir/src/myf.c.s"
	/usr/bin/cc  $(C_DEFINES) $(C_INCLUDES) $(C_FLAGS) -S /home/sapofree/Projeto_Bases_de_Dados/Clientes/cl_10001/src/myf.c -o CMakeFiles/simulador.dir/src/myf.c.s

CMakeFiles/simulador.dir/src/myf.c.o.requires:

.PHONY : CMakeFiles/simulador.dir/src/myf.c.o.requires

CMakeFiles/simulador.dir/src/myf.c.o.provides: CMakeFiles/simulador.dir/src/myf.c.o.requires
	$(MAKE) -f CMakeFiles/simulador.dir/build.make CMakeFiles/simulador.dir/src/myf.c.o.provides.build
.PHONY : CMakeFiles/simulador.dir/src/myf.c.o.provides

CMakeFiles/simulador.dir/src/myf.c.o.provides.build: CMakeFiles/simulador.dir/src/myf.c.o


# Object files for target simulador
simulador_OBJECTS = \
"CMakeFiles/simulador.dir/src/simulador.c.o" \
"CMakeFiles/simulador.dir/src/myf.c.o"

# External object files for target simulador
simulador_EXTERNAL_OBJECTS =

simulador: CMakeFiles/simulador.dir/src/simulador.c.o
simulador: CMakeFiles/simulador.dir/src/myf.c.o
simulador: CMakeFiles/simulador.dir/build.make
simulador: CMakeFiles/simulador.dir/link.txt
	@$(CMAKE_COMMAND) -E cmake_echo_color --switch=$(COLOR) --green --bold --progress-dir=/home/sapofree/Projeto_Bases_de_Dados/Clientes/cl_10001/build/CMakeFiles --progress-num=$(CMAKE_PROGRESS_3) "Linking C executable simulador"
	$(CMAKE_COMMAND) -E cmake_link_script CMakeFiles/simulador.dir/link.txt --verbose=$(VERBOSE)

# Rule to build all files generated by this target.
CMakeFiles/simulador.dir/build: simulador

.PHONY : CMakeFiles/simulador.dir/build

CMakeFiles/simulador.dir/requires: CMakeFiles/simulador.dir/src/simulador.c.o.requires
CMakeFiles/simulador.dir/requires: CMakeFiles/simulador.dir/src/myf.c.o.requires

.PHONY : CMakeFiles/simulador.dir/requires

CMakeFiles/simulador.dir/clean:
	$(CMAKE_COMMAND) -P CMakeFiles/simulador.dir/cmake_clean.cmake
.PHONY : CMakeFiles/simulador.dir/clean

CMakeFiles/simulador.dir/depend:
	cd /home/sapofree/Projeto_Bases_de_Dados/Clientes/cl_10001/build && $(CMAKE_COMMAND) -E cmake_depends "Unix Makefiles" /home/sapofree/Projeto_Bases_de_Dados/Clientes/cl_10001 /home/sapofree/Projeto_Bases_de_Dados/Clientes/cl_10001 /home/sapofree/Projeto_Bases_de_Dados/Clientes/cl_10001/build /home/sapofree/Projeto_Bases_de_Dados/Clientes/cl_10001/build /home/sapofree/Projeto_Bases_de_Dados/Clientes/cl_10001/build/CMakeFiles/simulador.dir/DependInfo.cmake --color=$(COLOR)
.PHONY : CMakeFiles/simulador.dir/depend

