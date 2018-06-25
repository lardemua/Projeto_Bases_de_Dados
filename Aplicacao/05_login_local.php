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
            <input type=\"submit\" value=\"Administração\" disabled>
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

<?php
    require('query_databases.php');
?>

<form action="05_login_local.php" method="post">
    <table>
        <tr>
        <td>Nome Base de Dados:</td>
        <td>
            <input type="text" name="database" value="">
        </td>
    </tr>
    </table>
    <input type="submit" name="Select_Database" value="Conectar" style="float:left;">
    <input type="submit" name="Deselect_Database" value="Desconectar">
</form>

<?php
    if(isset($_POST['Select_Database']))
    {
        $data_missing = array();
        $data_wrong = array();
        $go = 0;

        if(empty($_POST['database']))
        {
            $data_missing[] = 'Nome da Base de Dados';
        }else{
            $_SESSION['local_name'] = trim($_POST['database']);
        }

        if(empty($data_missing))
        {
            $go = 1;
        }else
        {
            echo "Faltam os seguintes campos: <br/>";
            foreach($data_missing as $missing)
            {
                echo "$missing<br/>";
            }
            $go = 0;
        }
        if($go)
        {
            require('local_connect.php');
            require('central_connect.php');
            require('temp_local_connect.php');
            require('query_copiar_cliente.php');
            mysqli_close($dbc2);
            mysqli_close($dbc);
            mysqli_close($dbc4);

            ob_start(); // ensures anything dumped out will be caught
            $url = '03_administracao.php'; // this can be set based on whatever

            // clear out the output buffer
            while (ob_get_status()) 
            {
                ob_end_clean();
            }

            // no redirect
            header( "Location: $url" );
        }
    }

    if(isset($_POST['Deselect_Database']))
    {
            $_SESSION['local_name'] = "";
            $_SESSION['local_status'] = "Connect";

            ob_start(); // ensures anything dumped out will be caught
            $url = '03_administracao.php'; // this can be set based on whatever

            // clear out the output buffer
            while (ob_get_status()) 
            {
                ob_end_clean();
            }

            // no redirect
            header( "Location: $url" );
    }

?>

</body>

</html>
