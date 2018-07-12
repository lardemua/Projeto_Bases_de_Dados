<?php
session_start();
?>
<html lang="pt">

<head>
    <meta charset="UTF-8" />
    <title>Conectar Local</title>
</head>

<body style="background-color:azure;">
<?php
    if($_SESSION['central_status'] != "Logout")
        {
            ob_start(); // ensures anything dumped out will be caught
            $url = 'index.php'; // this can be set based on whatever

            // clear out the output buffer
            while (ob_get_status()) 
            {
                ob_end_clean();
            }

            // no redirect
            header( "Location: $url" );

        }
?>
    <h1>Aplicação</h1>
    <form action="index.php" style="float: left;">
        <input type="submit" value="Home">
    </form>
    <form action="02_consultas.php" style="float: left;">
        <input type="submit" value="Consultas">
    </form>
    <form action="03_administracao.php" style="float: left;">
        <input type="submit" value="Administração Local">
    </form>
    <form action="04_login.php">
        <input type="submit" value="<?php echo $_SESSION['central_status']; ?>">
    </form>
    <?php
        if($_SESSION['central_status'] == "Logout" && $_SESSION['local_status'] == "Disconnect")
        {
	echo "<form action=\"031_admin_cliente.php\" style=\"float: left;\">
            <input type=\"submit\" value=\"Administração\">
            </form>";
            echo "<form action=\"05_login_local.php\">
            <input type=\"submit\" value=\"" . $_SESSION['local_name'] . "\">
            </form>";
        }else if($_SESSION['central_status'] == "Logout" && $_SESSION['local_status'] != "Disconnect")
        {
	echo "<form action=\"031_admin_cliente.php\" style=\"float: left;\">
            <input type=\"submit\" value=\"Administração\">
            </form>";
            echo "<form action=\"05_login_local.php\">
            <input type=\"submit\" value=\"Conectar Local\">
            </form>";
        }
    ?>
    <form action="05_login_local.php" style="float: left;">
        <input type="submit" value="Conectar">
    </form>
    <form action="051_criar_local.php" style="float: left;">
        <input type="submit" value="Criar Local">
    </form>
    <form action="052_instalar_local.php">
        <input type="submit" value="Instalar MySQL">
    </form>

 <div id="secretInfo" style="display: none;">
<?php
echo "
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
        CONSTRAINT SENSOR_MAU_ID_MOLDE_SENSOR
        FOREIGN KEY(s_IDMolde) REFERENCES moldes(m_ID)
            ON DELETE CASCADE ON UPDATE CASCADE,
        CONSTRAINT SENSOR_MAU_NOME_TIPO
        FOREIGN KEY(s_tipo) REFERENCES tipo(tipo_ID)
            ON DELETE NO ACTION ON UPDATE CASCADE
    );
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
    CREATE USER 'user'@'%' IDENTIFIED BY 'password';
    GRANT SELECT, INSERT, DELETE ON temp_local.* TO 'user'@'%';
    CREATE USER 'sensores'@'localhost' IDENTIFIED BY 'sensores1234';
    CREATE USER 'transferencia'@'%' IDENTIFIED BY 'transferencia1234';
    FLUSH PRIVILEGES;
";?>
</div>

        <p>Para instalar o MySQL abrir o terminal com Ctrl+Alt+t e introduzir o seguinte comando:</p>
        <pre>       sudo apt-get install mysql-server</pre>
        <p>Permitir ligações externas ao MySQL:</p>
        <pre>      sudo nano /etc/mysql/mysql.conf.d/mysqld.cnf</pre>
        <p>Comentar com # a linha:</p>
        <pre>       bind-address = 127.0.0.1</pre>
        <p>Reiniciar o servidor:</p>
        <pre>      sudo /etc/init.d/mysql restart</pre>
        <p>Iniciar sessão no MySQL:</p>
        <pre>      mysql -u root -p</pre>
        <p>Carregar no botão e inserir a query com ctrl+v ou colar:</p>
        <pre>---><button type="button" id="btnCopy">Copiar Query</button><---</pre>
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

</body>

</html>
