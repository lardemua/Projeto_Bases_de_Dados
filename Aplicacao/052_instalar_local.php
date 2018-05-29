<?php
session_start();
?>
<html lang="pt">

<head>
    <meta charset="UTF-8" />
    <title>Login</title>
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
        <input type="submit" value="Administração">
    </form>
    <?php
        if($_SESSION['central_status'] == "Logout" && $_SESSION['local_status'] == "Disconnect")
        {
            echo "<form action=\"05_login_local.php\" style=\"float: left;\">
            <input type=\"submit\" value=\"" . $_SESSION['local_name'] . "\">
            </form>";
        }else if($_SESSION['central_status'] == "Logout" && $_SESSION['local_status'] != "Disconnect")
        {
            echo "<form action=\"05_login_local.php\" style=\"float: left;\">
            <input type=\"submit\" value=\"Conectar Local\">
            </form>";
        }
    ?>
    <form action="04_login.php">
        <input type="submit" value="<?php echo $_SESSION['central_status']; ?>">
    </form>
    <form action="05_login_local.php" style="float: left;">
        <input type="submit" value="Conectar">
    </form>
    <form action="051_criar_local.php" style="float: left;">
        <input type="submit" value="Criar Local">
    </form>
    <form action="052_instalar_local.php">
        <input type="submit" value="Instalar MySQL">
    </form>

        <p>Para instalar o MySQL abrir o terminal com Ctrl+Shift+t e introduzir o seguinte comando:</p>
        <pre>       sudo apt-get install mysql-server</pre>
        <p>Permitir ligações externas ao MySQL:</p>
        <pre>      sudo nano /etc/mysql/mysql.conf.d/mysqld.cnf</pre>
        <p>Comentar com # a linha:</p>
        <pre>       bind-address = 127.0.0.1</pre>
        <p>Reiniciar o servidor:</p>
        <pre>      sudo /etc/init.d/mysql restart</pre>
        <p>Inciar sessão no MySQL:</p>
        <pre>      mysql -u root -p</pre>
        <p>Introduzir a query:</p>
        <pre>      CREATE USER 'user'@'%' IDENTIFIED BY '<font color="azure">password</font>';
      GRANT CREATE ON *.* TO 'user'@'%';
      FLUSH PRIVILEGES;</pre>
        <p>Terminar sessão:</p>
        <pre>      quit</pre>

</body>

</html>