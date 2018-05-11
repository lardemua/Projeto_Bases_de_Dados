<html lang="pt">
<head>
    <meta charset="UTF-8" />
    <title>Administração</title>
    <style>
    table.query tr:nth-child(even) {
        background-color: #dddddd;
    }
</style>
</head>
<body>
<?php
    DEFINE('DB_USER_Local','root');
    DEFINE('DB_PASSWORD_Local',$_POST['root_pass']);
    DEFINE('DB_HOST_Local','localhost');

    $dbc3 = @mysqli_connect(DB_HOST_Local,DB_USER_Local,DB_PASSWORD_Local);

    // Check connection
    if (!$dbc3) {
        die('Could not connect to MySQL ' . mysqli_connect_error() . "<br>");
    }else {
    }

    // Change character set to utf8
    mysqli_set_charset($dbc3,"utf8");

    $query = "CREATE DATABASE cl_" . $_POST['cl_id'];

    if (!mysqli_query($dbc3,$query))
    {
        echo("Erro: " . mysqli_error($dbc3)) . "<br>";
    }
    mysqli_close($dbc3);

    $db = "cl_" . $_POST['cl_id'];

    $dbc3 = @mysqli_connect(DB_HOST_Local,DB_USER_Local,DB_PASSWORD_Local,$db);

    // Check connection
    if (!$dbc3) {
        die('Could not connect to MySQL ' . mysqli_connect_error() . "<br>");
    }else {
    }

    // Change character set to utf8
    mysqli_set_charset($dbc3,"utf8");

    $query = "CREATE TABLE clientes
    (
        cl_ID INT NOT NULL,
        cl_nome VARCHAR(50) NOT NULL,
        cl_morada VARCHAR(100),
        cl_IP VARCHAR(50) NOT NULL,
        cl_port INT NOT NULL,
        CONSTRAINT REPETIDO_ID_CLIENTE
        PRIMARY KEY(cl_ID)
    )";

    if (!mysqli_query($dbc3,$query))
    {
        echo("Erro: " . mysqli_error($dbc3) . "<br>");
    }
    mysqli_close($dbc3);

    $dbc3 = @mysqli_connect(DB_HOST_Local,DB_USER_Local,DB_PASSWORD_Local,$db);

    // Check connection
    if (!$dbc3) {
        die('Could not connect to MySQL ' . mysqli_connect_error() . "<br>");
    }else {
    }

    // Change character set to utf8
    mysqli_set_charset($dbc3,"utf8");

    $query = "CREATE TABLE moldes
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
    )";

    if (!mysqli_query($dbc3,$query))
    {
        echo("Erro: " . mysqli_error($dbc3) . "<br>");
    }
    mysqli_close($dbc3);

    $dbc3 = @mysqli_connect(DB_HOST_Local,DB_USER_Local,DB_PASSWORD_Local,$db);

    // Check connection
    if (!$dbc3) {
        die('Could not connect to MySQL ' . mysqli_connect_error() . "<br>");
    }else {
    }

    // Change character set to utf8
    mysqli_set_charset($dbc3,"utf8");

    $query = "CREATE TABLE tipo
    (
        tipo_ID INT NOT NULL,
        tipo_nome VARCHAR(50) NOT NULL,
        CONSTRAINT REPETIDO_ID_TIPO
        PRIMARY KEY(tipo_ID),
        CONSTRAINT REPETIDO_NOME_TIPO
        UNIQUE(tipo_nome)
    )";

    if (!mysqli_query($dbc3,$query))
    {
        echo("Erro: " . mysqli_error($dbc3) . "<br>");
    }
    mysqli_close($dbc3);

    $dbc3 = @mysqli_connect(DB_HOST_Local,DB_USER_Local,DB_PASSWORD_Local,$db);

    // Check connection
    if (!$dbc3) {
        die('Could not connect to MySQL ' . mysqli_connect_error() . "<br>");
    }else {
    }

    // Change character set to utf8
    mysqli_set_charset($dbc3,"utf8");

    $query = "CREATE TABLE sensores
    (
        s_IDMolde INT NOT NULL,
        s_num INT NOT NULL,
        s_tipo INT NOT NULL,
        s_nome varchar(30),
        s_descricao varchar(200),
        CONSTRAINT REPETIDO_NUM_SENSOR
        PRIMARY KEY(s_IDMolde,s_num),
        CONSTRAINT SENSOR_MAU_ID_MOLDE_SENSOR
        FOREIGN KEY(s_IDMolde) REFERENCES moldes(m_ID)
            ON DELETE CASCADE ON UPDATE CASCADE,
        CONSTRAINT SENSOR_MAU_NOME_TIPO
        FOREIGN KEY(s_tipo) REFERENCES tipo(tipo_ID)
            ON DELETE NO ACTION ON UPDATE CASCADE
    )";

    if (!mysqli_query($dbc3,$query))
    {
        echo("Erro: " . mysqli_error($dbc3) . "<br>");
    }
    mysqli_close($dbc3);

    $dbc3 = @mysqli_connect(DB_HOST_Local,DB_USER_Local,DB_PASSWORD_Local,$db);

    // Check connection
    if (!$dbc3) {
        die('Could not connect to MySQL ' . mysqli_connect_error() . "<br>");
    }else {
    }

    // Change character set to utf8
    mysqli_set_charset($dbc3,"utf8");

    $query = "CREATE TABLE registos
    (
        r_IDMolde INT NOT NULL,
        r_numSensor INT NOT NULL,
        r_data_hora DATETIME NOT NULL,
        r_milisegundos TINYINT NOT NULL,
        r_valor FLOAT,
        CONSTRAINT REPETIDO_REGISTO
        PRIMARY KEY(r_IDMolde, r_numSensor, r_data_hora, r_milisegundos),
        CONSTRAINT REGISTOS_MAU_ID_MOLDE_SENSOR
    FOREIGN KEY(r_IDMolde,r_numSensor) REFERENCES sensores(s_IDMolde,s_num)
            ON DELETE CASCADE ON UPDATE CASCADE
    )";

    if (!mysqli_query($dbc3,$query))
    {
        echo("Erro: " . mysqli_error($dbc3) . "<br>");
    }
    mysqli_close($dbc3);

    $dbc3 = @mysqli_connect(DB_HOST_Local,DB_USER_Local,DB_PASSWORD_Local,$db);

    // Check connection
    if (!$dbc3) {
        die('Could not connect to MySQL ' . mysqli_connect_error() . "<br>");
    }else {
    }

    // Change character set to utf8
    mysqli_set_charset($dbc3,"utf8");

    $query = "INSERT INTO tipo
    VALUES
        (1, \"Termómetro\"),
        (2, \"Dinamómetro\"),
        (3, \"Extensómetro\"),
        (4, \"Vibrómetro\"),
        (5, \"Pressão\"),
        (6, \"Acelerómetro X\"),
        (7, \"Acelerómetro Y\"),
        (8, \"Acelerómetro Z\")";

    if (!mysqli_query($dbc3,$query))
    {
        echo("Erro: " . mysqli_error($dbc3) . "<br>");
    }
    mysqli_close($dbc3);

    echo "Base de dados local criada"

?>
</body>
</html>
