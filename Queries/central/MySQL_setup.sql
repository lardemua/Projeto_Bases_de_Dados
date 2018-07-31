CREATE DATABASE central;
USE central;
CREATE TABLE tipo
(
	tipo_ID INT NOT NULL,
	tipo_nome VARCHAR(50) NOT NULL,
	CONSTRAINT REPETIDO_ID_TIPO
	PRIMARY KEY(tipo_ID),
	CONSTRAINT REPETIDO_NOME_TIPO
	UNIQUE(tipo_nome)
);
CREATE TABLE fase
(
	fase_ID INT NOT NULL,
	fase_nome VARCHAR(50) NOT NULL,
	CONSTRAINT REPETIDO_ID_FASE
	PRIMARY KEY(FASE_ID),
	CONSTRAINT REPETIDO_NOME_FASE
	UNIQUE(fase_nome)
);
CREATE TABLE clientes
(
    cl_ID INT NOT NULL,
    cl_nome VARCHAR(50) NOT NULL,
    cl_morada VARCHAR(100),
    cl_IP VARCHAR(50) NOT NULL,
    cl_port INT NOT NULL,
    CONSTRAINT REPETIDO_ID_CLIENTE
	PRIMARY KEY(cl_ID)
);
CREATE TABLE moldes
(
    m_IDCliente INT NOT NULL,
    m_ID INT NOT NULL,
    m_nome VARCHAR(30),
    m_descricao VARCHAR(200),
    CONSTRAINT REPETIDO_ID_MOLDE
	PRIMARY KEY(m_ID),
    CONSTRAINT MOLDE_MAU_ID_CLIENTE
	FOREIGN KEY(m_IDCliente) REFERENCES clientes(cl_ID)
		ON DELETE NO ACTION ON UPDATE NO ACTION
);
CREATE TABLE sensores
(
	s_IDMolde INT NOT NULL,
	s_num INT NOT NULL,
	s_tipo INT NOT NULL,
	s_nome varchar(30),
	s_descricao varchar(200),
	CONSTRAINT REPETIDO_NUM_SENSOR
	PRIMARY KEY(s_IDMolde,s_num),
	CONSTRAINT SENSOR_MAU_ID_MOLDE
	FOREIGN KEY(s_IDMolde) REFERENCES moldes(m_ID)
		ON DELETE CASCADE ON UPDATE NO ACTION,
	CONSTRAINT SENSOR_MAU_ID_TIPO
	FOREIGN KEY(s_tipo) REFERENCES tipo(tipo_ID)
		ON DELETE NO ACTION ON UPDATE NO ACTION
);
CREATE TABLE registos
(
	r_IDMolde INT NOT NULL,
	r_numSensor INT NOT NULL,
	r_fase INT NOT NULL,
	r_data_hora DATETIME NOT NULL,
	r_milissegundos TINYINT NOT NULL,
	r_valor FLOAT,
	CONSTRAINT REPETIDO_REGISTO
	PRIMARY KEY(r_IDMolde, r_numSensor, r_data_hora, r_milissegundos),
	CONSTRAINT REGISTOS_MAU_ID_MOLDE_SENSOR
FOREIGN KEY(r_IDMolde,r_numSensor) REFERENCES sensores(s_IDMolde,s_num)
		ON DELETE CASCADE ON UPDATE NO ACTION,
	CONSTRAINT REGISTOS_MAU_ID_FASE
FOREIGN KEY(r_fase) REFERENCES fase(fase_ID)
		ON DELETE NO ACTION ON UPDATE NO ACTION
);
CREATE DATABASE backups;
USE backups;
CREATE TABLE tipo
(
	tipo_ID INT NOT NULL,
	tipo_nome VARCHAR(50) NOT NULL,
	CONSTRAINT REPETIDO_ID_TIPO
	PRIMARY KEY(tipo_ID),
	CONSTRAINT REPETIDO_NOME_TIPO
	UNIQUE(tipo_nome)
);
CREATE TABLE fase
(
	fase_ID INT NOT NULL,
	fase_nome VARCHAR(50) NOT NULL,
	CONSTRAINT REPETIDO_ID_FASE
	PRIMARY KEY(FASE_ID),
	CONSTRAINT REPETIDO_NOME_FASE
	UNIQUE(fase_nome)
);
CREATE TABLE clientes
(
    cl_ID INT NOT NULL,
    cl_nome VARCHAR(50) NOT NULL,
    cl_morada VARCHAR(100),
    cl_IP VARCHAR(50) NOT NULL,
    cl_port INT NOT NULL,
    CONSTRAINT REPETIDO_ID_CLIENTE
	PRIMARY KEY(cl_ID)
);
CREATE TABLE moldes
(
    m_IDCliente INT NOT NULL,
    m_ID INT NOT NULL,
    m_nome VARCHAR(30),
    m_descricao VARCHAR(200),
    CONSTRAINT REPETIDO_ID_MOLDE
	PRIMARY KEY(m_ID),
    CONSTRAINT MOLDE_MAU_ID_CLIENTE
	FOREIGN KEY(m_IDCliente) REFERENCES clientes(cl_ID)
		ON DELETE NO ACTION ON UPDATE NO ACTION
);
CREATE TABLE sensores

(
	s_IDMolde INT NOT NULL,
	s_num INT NOT NULL,
	s_tipo INT NOT NULL,
	s_nome varchar(30),
	s_descricao varchar(200),
	CONSTRAINT REPETIDO_NUM_SENSOR
	PRIMARY KEY(s_IDMolde,s_num),
	CONSTRAINT SENSOR_MAU_ID_MOLDE
	FOREIGN KEY(s_IDMolde) REFERENCES moldes(m_ID)
		ON DELETE CASCADE ON UPDATE NO ACTION,
	CONSTRAINT SENSOR_MAU_ID_TIPO
	FOREIGN KEY(s_tipo) REFERENCES tipo(tipo_ID)
		ON DELETE NO ACTION ON UPDATE NO ACTION
);
CREATE TABLE registos
(
	r_IDMolde INT NOT NULL,
	r_numSensor INT NOT NULL,
	r_fase INT NOT NULL,
	r_data_hora DATETIME NOT NULL,
	r_milissegundos TINYINT NOT NULL,
	r_valor FLOAT,
	CONSTRAINT REPETIDO_REGISTO
	PRIMARY KEY(r_IDMolde, r_numSensor, r_data_hora, r_milissegundos),
	CONSTRAINT REGISTOS_MAU_ID_MOLDE_SENSOR
FOREIGN KEY(r_IDMolde,r_numSensor) REFERENCES sensores(s_IDMolde,s_num)
		ON DELETE CASCADE ON UPDATE NO ACTION,
	CONSTRAINT REGISTOS_MAU_ID_FASE
FOREIGN KEY(r_fase) REFERENCES fase(fase_ID)
		ON DELETE NO ACTION ON UPDATE NO ACTION
);
CREATE DATABASE backups_temp;
USE backups_temp;
CREATE TABLE tipo
(
	tipo_ID INT NOT NULL,
	tipo_nome VARCHAR(50) NOT NULL,
	CONSTRAINT REPETIDO_ID_TIPO
	PRIMARY KEY(tipo_ID),
	CONSTRAINT REPETIDO_NOME_TIPO
	UNIQUE(tipo_nome)
);
CREATE TABLE fase
(
	fase_ID INT NOT NULL,
	fase_nome VARCHAR(50) NOT NULL,
	CONSTRAINT REPETIDO_ID_FASE
	PRIMARY KEY(FASE_ID),
	CONSTRAINT REPETIDO_NOME_FASE
	UNIQUE(fase_nome)
);
CREATE TABLE clientes
(
    cl_ID INT NOT NULL,
    cl_nome VARCHAR(50) NOT NULL,
    cl_morada VARCHAR(100),
    cl_IP VARCHAR(50) NOT NULL,
    cl_port INT NOT NULL,
    CONSTRAINT REPETIDO_ID_CLIENTE
	PRIMARY KEY(cl_ID)
);
CREATE TABLE moldes
(
    m_IDCliente INT NOT NULL,
    m_ID INT NOT NULL,
    m_nome VARCHAR(30),
    m_descricao VARCHAR(200),
    CONSTRAINT REPETIDO_ID_MOLDE
	PRIMARY KEY(m_ID),
    CONSTRAINT MOLDE_MAU_ID_CLIENTE
	FOREIGN KEY(m_IDCliente) REFERENCES clientes(cl_ID)
		ON DELETE NO ACTION ON UPDATE NO ACTION
);
CREATE TABLE sensores

(
	s_IDMolde INT NOT NULL,
	s_num INT NOT NULL,
	s_tipo INT NOT NULL,
	s_nome varchar(30),
	s_descricao varchar(200),
	CONSTRAINT REPETIDO_NUM_SENSOR
	PRIMARY KEY(s_IDMolde,s_num),
	CONSTRAINT SENSOR_MAU_ID_MOLDE
	FOREIGN KEY(s_IDMolde) REFERENCES moldes(m_ID)
		ON DELETE CASCADE ON UPDATE NO ACTION,
	CONSTRAINT SENSOR_MAU_ID_TIPO
	FOREIGN KEY(s_tipo) REFERENCES tipo(tipo_ID)
		ON DELETE NO ACTION ON UPDATE NO ACTION
);
CREATE TABLE registos
(
	r_IDMolde INT NOT NULL,
	r_numSensor INT NOT NULL,
	r_fase INT NOT NULL,
	r_data_hora DATETIME NOT NULL,
	r_milissegundos TINYINT NOT NULL,
	r_valor FLOAT,
	CONSTRAINT REPETIDO_REGISTO
	PRIMARY KEY(r_IDMolde, r_numSensor, r_data_hora, r_milissegundos),
	CONSTRAINT REGISTOS_MAU_ID_MOLDE_SENSOR
FOREIGN KEY(r_IDMolde,r_numSensor) REFERENCES sensores(s_IDMolde,s_num)
		ON DELETE CASCADE ON UPDATE NO ACTION,
	CONSTRAINT REGISTOS_MAU_ID_FASE
FOREIGN KEY(r_fase) REFERENCES fase(fase_ID)
		ON DELETE NO ACTION ON UPDATE NO ACTION
);
CREATE DATABASE reg_proc;
USE reg_proc;
CREATE TABLE atualizar
(
	a_indice INT NOT NULL,
	a_transferencia INT NOT NULL,
	a_backups INT NOT NULL,
	CONSTRAINT REPETIDO_INDICE_ATUALIZAR
		PRIMARY KEY(a_indice),
	CONSTRAINT ATUALIZAR_MAU_INDICE
		CHECK (a_indice = 1),
	CONSTRAINT ATUALIZAR_MAU_TRANSFERENCIA
		CHECK (a_transferencia = 0 OR a_transferencia = 1),
	CONSTRAINT ATUALIZAR_MAU_BACKUPS
		CHECK (a_backups = 0 OR a_backups = 1)
);
CREATE TABLE backups
(
	b_IDMolde INT NOT NULL,
	CONSTRAINT REPETIDO_ID_MOLDE
		PRIMARY KEY(b_IDMolde)
);
USE central;
INSERT INTO tipo
VALUES
    (1, "Termómetro"),
    (2, "Dinamómetro"),
    (3, "Extensómetro"),
    (4, "Vibrómetro"),
    (5, "Pressão"),
    (6, "Acelerómetro X"),
    (7, "Acelerómetro Y"),
    (8, "Acelerómetro Z");
INSERT INTO fase
VALUES
	(1, "Fecho"),
	(2, "Enchimento"),
	(3, "Compactação"),
	(4, "Abertura"),
	(5, "Extração");
USE backups;
INSERT INTO tipo
VALUES
    (1, "Termómetro"),
    (2, "Dinamómetro"),
    (3, "Extensómetro"),
    (4, "Vibrómetro"),
    (5, "Pressão"),
    (6, "Acelerómetro X"),
    (7, "Acelerómetro Y"),
    (8, "Acelerómetro Z");
INSERT INTO fase
VALUES
	(1, "Fecho"),
	(2, "Enchimento"),
	(3, "Compactação"),
	(4, "Abertura"),
	(5, "Extração");
USE backups_temp;
INSERT INTO tipo
VALUES
    (1, "Termómetro"),
    (2, "Dinamómetro"),
    (3, "Extensómetro"),
    (4, "Vibrómetro"),
    (5, "Pressão"),
    (6, "Acelerómetro X"),
    (7, "Acelerómetro Y"),
    (8, "Acelerómetro Z");
INSERT INTO fase
VALUES
	(1, "Fecho"),
	(2, "Enchimento"),
	(3, "Compactação"),
	(4, "Abertura"),
	(5, "Extração");
USE reg_proc;
INSERT INTO atualizar
VALUES
	(1, 0, 0);
