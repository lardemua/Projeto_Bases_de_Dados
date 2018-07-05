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
require('central_connect.php');
$query = "SELECT cl_ID, cl_nome, cl_morada, cl_IP, cl_port FROM clientes WHERE cl_ID = " . $_POST['cl_id'];

$response = @mysqli_query($dbc,$query);

if($response)
{
    while($row = mysqli_fetch_array($response))
    {
        if(is_null($row['cl_morada']))
        {
            $row['cl_morada'] = "NULL";
        }
        $cl_ID = $row['cl_ID'];
        $cl_nome = $row['cl_nome'];
        $cl_morada = $row['cl_morada'];
        $cl_IP = $row['cl_IP'];
        $cl_port = $row['cl_port'];
    }
} else{
    echo "Error: ";

    echo mysqli_error($dbc);
}
?>

 <div id="secretInfo" style="display: none;">
<?php
echo "
    USE temp_local;
    INSERT IGNORE clientes VALUES
    (" . $cl_ID . ",'" .$cl_nome . "','" .$cl_morada . "','" .$cl_IP . "'," .$cl_port . ");
    CREATE DATABASE cl_" . $_POST['cl_id'] . ";
    USE cl_" . $_POST['cl_id'] . ";
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
        CONSTRAINT SENSOR_MAU_ID_MOLDE_SENSOR
        FOREIGN KEY(s_IDMolde) REFERENCES moldes(m_ID)
            ON DELETE CASCADE ON UPDATE CASCADE,
        CONSTRAINT SENSOR_MAU_NOME_TIPO
        FOREIGN KEY(s_tipo) REFERENCES tipo(tipo_ID)
            ON DELETE NO ACTION ON UPDATE CASCADE
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
            ON DELETE CASCADE ON UPDATE CASCADE,
        CONSTRAINT REGISTOS_MAU_ID_FASE
    FOREIGN KEY(r_fase) REFERENCES fase(fase_ID)
            ON DELETE NO ACTION ON UPDATE CASCADE
    );
    INSERT IGNORE clientes VALUES
    (" . $cl_ID . ",'" .$cl_nome . "','" .$cl_morada . "','" .$cl_IP . "'," .$cl_port . ");
    INSERT INTO tipo
    VALUES
    (1, 'Termómetro'),
    (2, 'Dinamómetro'),
    (3, 'Extensómetro'),
    (4, 'Vibrómetro'),
    (5, 'Pressão'),
    (6, 'Acelerómetro X'),
    (7, 'Acelerómetro Y'),
    (8, 'Acelerómetro Z');
    INSERT INTO fase
    VALUES
    (1, 'Fecho'),
    (2, 'Enchimento'),
    (3, 'Compactação'),
    (4, 'Abertura'),
    (5, 'Extração');
    GRANT SELECT, INSERT, UPDATE ON cl_" . $_POST['cl_id'] . ".clientes TO 'user'@'%';
    GRANT SELECT, INSERT, DELETE ON cl_" . $_POST['cl_id'] . ".moldes TO 'user'@'%';
    GRANT SELECT, INSERT, DELETE ON cl_" . $_POST['cl_id'] . ".sensores TO 'user'@'%';
    GRANT SELECT ON cl_" . $_POST['cl_id'] . ".tipo TO 'user'@'%';
    GRANT SELECT ON cl_" . $_POST['cl_id'] . ".fase TO 'user'@'%';
    GRANT SELECT ON cl_" . $_POST['cl_id'] . ".registos TO 'user'@'%';
    GRANT INSERT ON cl_" . $_POST['cl_id'] . ".registos TO 'sensores'@'localhost';
    GRANT SELECT, DELETE ON cl_" . $_POST['cl_id'] . ".registos TO 'transferencia'@'%';
    FLUSH PRIVILEGES;
";?>
</div>

<p>Inciar sessão no MySQL:</p>
<pre>      mysql -u root -p</pre>
<p>Query gerada, carregar no botão:</p>
<pre>---><button type="button" id="btnCopy">Copiar Query</button><---</pre>
<p>Inserir a query com ctrl+v ou colar</p>
<p>Terminar sessão:</p>
<pre>      quit</pre>

    <script type="text/javascript">
      var $body = document.getElementsByTagName('body')[0];
      var $btnCopy = document.getElementById('btnCopy');
      var secretInfo = document.getElementById('secretInfo').innerHTML;
      var copyToClipboard = function(secretInfo) {
        var $tempInput = document.createElement('INPUT');
        $body.appendChild($tempInput);
        $tempInput.setAttribute('value', secretInfo)
        $tempInput.select();
        document.execCommand('copy');
        $body.removeChild($tempInput);
      }
      $btnCopy.addEventListener('click', function(ev) {
        copyToClipboard(secretInfo);
      });
    </script>

<!-- PRIMEIRA TENTATIVA, FUNCIONA MAS DEMORA MUITO TEMPO E CAUSA TIMEOUT DAS CENAS
<?php
	/*
    DEFINE('DB_USER_Local',$_SESSION['user']);
    DEFINE('DB_PASSWORD_Local',$_SESSION['password']);
    DEFINE('DB_HOST_Local',$_SERVER['REMOTE_ADDR']);

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

    $query = "CREATE TABLE fase
    (
        fase_ID INT NOT NULL,
        fase_nome VARCHAR(50) NOT NULL,
        CONSTRAINT REPETIDO_ID_FASE
        PRIMARY KEY(FASE_ID),
        CONSTRAINT REPETIDO_NOME_FASE
        UNIQUE(fase_nome)
    )";

    if (!mysqli_query($dbc3,$query))
    {
        echo("Erro: " . mysqli_error($dbc3) . "<br>");
    }    

    $query = "CREATE TABLE registos
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
            ON DELETE CASCADE ON UPDATE CASCADE,
        CONSTRAINT REGISTOS_MAU_ID_FASE
    FOREIGN KEY(r_fase) REFERENCES fase(fase_ID)
            ON DELETE NO ACTION ON UPDATE CASCADE
    )";

    if (!mysqli_query($dbc3,$query))
    {
        echo("Erro: " . mysqli_error($dbc3) . "<br>");
    }
    mysqli_close($dbc3);

    $dbc3 = @mysqli_connect(DB_HOST_Local,DB_USER_Local,DB_PASSWORD_Local);

    // Check connection
    if (!$dbc3) {
        die('Could not connect to MySQL ' . mysqli_connect_error() . "<br>");
    }else {
    }

    // Change character set to utf8
    mysqli_set_charset($dbc3,"utf8");

    $query = "CREATE DATABASE temp_local";

    if (!mysqli_query($dbc3,$query))
    {
        echo("Erro: " . mysqli_error($dbc3)) . "<br>";
    }
    mysqli_close($dbc3);

$db = "temp_local";

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

    $query = "CREATE TABLE fase
    (
        fase_ID INT NOT NULL,
        fase_nome VARCHAR(50) NOT NULL,
        CONSTRAINT REPETIDO_ID_FASE
        PRIMARY KEY(FASE_ID),
        CONSTRAINT REPETIDO_NOME_FASE
        UNIQUE(fase_nome)
    )";

    if (!mysqli_query($dbc3,$query))
    {
        echo("Erro: " . mysqli_error($dbc3) . "<br>");
    }    

    $query = "CREATE TABLE registos
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
            ON DELETE CASCADE ON UPDATE CASCADE,
        CONSTRAINT REGISTOS_MAU_ID_FASE
    FOREIGN KEY(r_fase) REFERENCES fase(fase_ID)
            ON DELETE NO ACTION ON UPDATE CASCADE
    )";

    if (!mysqli_query($dbc3,$query))
    {
        echo("Erro: " . mysqli_error($dbc3) . "<br>");
    }
    mysqli_close($dbc3);
*/
?>

<p>Base de dados local criada</p>
<p>Inciar sessão no MySQL:</p>
<pre>      mysql -u root -p</pre>
<p>Inserir as queries</p>
<pre>    GRANT SELECT, UPDATE, ON <?php echo  "cl_" . $_POST['cl_id'];?>.clientes TO 'user'@'%';
    GRANT SELECT, INSERT, DELETE ON <?php echo  "cl_" . $_POST['cl_id'];?>.moldes TO 'user'@'%';
    GRANT SELECT, INSERT, DELETE ON <?php echo  "cl_" . $_POST['cl_id'];?>.sensores TO 'user'@'%';
    GRANT SELECT ON <?php echo  "cl_" . $_POST['cl_id'];?>.registos TO 'user'@'%';
    CREATE USER 'sensores'@'localhost' IDENTIFIED BY '<font color="azure">sensores1234</font>';
    GRANT INSERT ON <?php echo  "cl_" . $_POST['cl_id'];?>.registos TO 'sensores'@'localhost';
    CREATE USER 'transferencia'@'%' IDENTIFIED BY '<font color="azure">transferencia1234</font>';
    GRANT SELECT, DELETE ON <?php echo  "cl_" . $_POST['cl_id'];?>.registos TO 'transferencia'@'%';
	GRANT SELECT, INSERT, DELETE ON temp_local.* TO 'user'@'localhost';
    FLUSH PRIVILEGES;
    USE <?php echo  "cl_" . $_POST['cl_id'];?>;
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
    USE temp_local;
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
    (5, "Extração");</pre>
<p>Terminar sessão:</p>
<pre>      quit</pre>
-->

</body>
</html>
