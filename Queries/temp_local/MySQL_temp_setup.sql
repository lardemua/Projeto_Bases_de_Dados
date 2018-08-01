CREATE DATABASE temp_local;
USE temp_local;
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
		ON DELETE NO ACTION ON UPDATE CASCADE
);
CREATE TABLE tipo
(
    tipo_ID INT NOT NULL,
    tipo_nome VARCHAR(50) NOT NULL,
    CONSTRAINT REPETIDO_ID_TIPO
	PRIMARY KEY(tipo_ID),
    CONSTRAINT REPETIDO_NOME_TIPO
	UNIQUE(tipo_nome)
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
		ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT SENSOR_MAU_ID_TIPO
	FOREIGN KEY(s_tipo) REFERENCES tipo(tipo_ID)
		ON DELETE NO ACTION ON UPDATE CASCADE
);