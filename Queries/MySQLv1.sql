%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%	   BACKUPS 	%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
mysqldump -u sapofree -pnaopossodizer backups > ~/Backups/backup3.sql
mysql -u sapofree -pnaopossodizer backups < Backups/backup2.sql
%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

SELECT table_schema                                        "DB Name", 
   Round(Sum(data_length + index_length) / 1024 / 1024, 1) "DB Size in MB" 
FROM   information_schema.tables 
GROUP  BY table_schema;

SELECT table_name AS `Table`, 
    round(((data_length + index_length) / 1024 / 1024), 2) `Size in MB` 
FROM information_schema.TABLES 
WHERE table_schema = "central"
    AND table_name = "registos";

Empresa:
CREATE TABLE Clientes(
     ID_cliente   INT UNSIGNED      NOT NULL,
     nome         VARCHAR(50)       NOT NULL,
     morada       VARCHAR(50),
     IP           INT UNSIGNED      NOT NULL,
     port         INT               NOT NULL,
     PRIMARY KEY(ID_cliente),
     UNIQUE(IP));

CREATE TABLE Moldes(
     ID_cliente   INT UNSIGNED      NOT NULL,
     ID_molde     INT UNSIGNED      NOT NULL,
     nome         VARCHAR(50),
     descrição    VARCHAR(500),
     PRIMARY KEY(ID_molde),
     FOREIGN KEY(ID_cliente) REFERENCES Clientes(ID_cliente));

CREATE TABLE Sensores(
     ID_molde         INT UNSIGNED      NOT NULL,
     numero_sensor    INT UNSIGNED      NOT NULL,
     nome             VARCHAR(50),
     tipo             VARCHAR(50)       NOT NULL,
     descrição        VARCHAR(500),
     PRIMARY KEY(ID_molde,numero_sensor),
     FOREIGN KEY(ID_molde) REFERENCES Moldes(ID_molde));

CREATE TABLE Registos(
     ID_molde         INT UNSIGNED      NOT NULL,
     numero_sensor    INT UNSIGNED      NOT NULL,
     data_hora        DATETIME          NOT NULL,
     milisegundos     TINYINT UNSIGNED  NOT NULL,
     valor            DOUBLE(7,3),
     PRIMARY KEY(ID_molde,numero_sensor,data_hora,milisegundos),
     FOREIGN KEY(ID_molde,numero_sensor) REFERENCES Sensores(ID_molde,numero_sensor));

Clientes:
CREATE TABLE Moldes(
     ID_molde     INT UNSIGNED      NOT NULL,
     nome         VARCHAR(50),
     descrição    VARCHAR(500),
     PRIMARY KEY(ID_molde));

CREATE TABLE Sensores(
     ID_molde         INT UNSIGNED      NOT NULL,
     numero_sensor    INT UNSIGNED      NOT NULL,
     nome             VARCHAR(50),
     tipo             VARCHAR(50)       NOT NULL,
     descrição        VARCHAR(500),
     PRIMARY KEY(ID_molde,numero_sensor),
     FOREIGN KEY(ID_molde) REFERENCES Moldes(ID_molde));

CREATE TABLE Registos(
     ID_molde         INT UNSIGNED      NOT NULL,
     numero_sensor    INT UNSIGNED      NOT NULL,
     data_hora        DATETIME          NOT NULL,
     milisegundos     TINYINT UNSIGNED  NOT NULL,
     valor            DOUBLE(7,3),
     PRIMARY KEY(ID_molde,numero_sensor,data_hora,milisegundos),
     FOREIGN KEY(ID_molde,numero_sensor) REFERENCES Sensores(ID_molde,numero_sensor));

Valores:
INSERT INTO Clientes VALUES (1,"cliente1",NULL,INET_ATON("127.0.0.1"),3306);
INSERT INTO Clientes VALUES (2,"cliente2",NULL,INET_ATON("127.0.0.1"),3306);
INSERT INTO Clientes VALUES (3,"cliente3",NULL,INET_ATON("127.0.0.1"),3306);
INSERT INTO Moldes VALUES (1,1,NULL,NULL);
INSERT INTO Moldes VALUES (2,20,NULL,NULL);
INSERT INTO Moldes VALUES (3,30,NULL,NULL);
INSERT INTO Sensores VALUES (1,1,NULL,"termometro",NULL);
INSERT INTO Sensores VALUES (1,2,NULL,"dinamometro",NULL);
INSERT INTO Sensores VALUES (1,3,NULL,"dinamometro",NULL);
INSERT INTO Sensores VALUES (1,4,NULL,"dinamometro",NULL);
INSERT INTO Sensores VALUES (1,5,NULL,"dinamometro",NULL);
INSERT INTO Sensores VALUES (20,1,NULL,"termometro",NULL);
INSERT INTO Sensores VALUES (20,2,NULL,"dinamometro",NULL);
INSERT INTO Sensores VALUES (20,3,NULL,"dinamometro",NULL);
INSERT INTO Sensores VALUES (20,4,NULL,"dinamometro",NULL);
INSERT INTO Sensores VALUES (20,5,NULL,"dinamometro",NULL);
INSERT INTO Sensores VALUES (30,1,NULL,"termometro",NULL);
INSERT INTO Sensores VALUES (30,2,NULL,"dinamometro",NULL);
INSERT INTO Sensores VALUES (30,3,NULL,"dinamometro",NULL);
INSERT INTO Sensores VALUES (30,4,NULL,"dinamometro",NULL);
INSERT INTO Sensores VALUES (30,5,NULL,"dinamometro",NULL);
