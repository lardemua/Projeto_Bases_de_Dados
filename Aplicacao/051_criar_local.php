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


<?php
    $id_clientes = array();
    require('query_criar_cliente.php');
?>

<form action="051_criar_local.php" method="post">
    <table>
    <tr>
        <td>ID Cliente:</td>
        <td>
            <input type="text" name="cl_id" value="">
        </td>
    </tr>
    <!--
    <tr>
        <td>Root Password:</td>
        <td>
            <input type="password" name="root_pass" value="">
        </td>
    </tr>
    -->
    <tr>
        <td>
            <input type="submit" name="Criar" value="Criar">
        </td>
    </tr>
    </table>
</form>

<?php
    if(isset($_POST['Criar']))
    {
        $data_missing = array();
        $data_wrong = array();
        $go = 0;

        if(empty($_POST['cl_id']))
        {
            $data_missing[] = 'ID Cliente';
        }else{
            $cl_id = trim($_POST['cl_id']);
        }
        if(!is_numeric($cl_id))
        {
            $data_wrong[] = 'ID Cliente';
        }
        /*
        if(empty($_POST['root_pass']))
        {
            $data_missing[] = 'Root Password';
        }else{
            $root_pass = trim($_POST['root_pass']);
        }
        */

        if(empty($data_missing))
        {
            if(empty($data_wrong))
            {
                $go = 1;
            }else
            {
                echo "Não são aceites os seguintes campos: <br/>";
                foreach($data_wrong as $wrong)
                {
                    echo "$wrong <br/>";
                }
                $go = 0;
            }
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
            if (in_array($_POST['cl_id'], $id_clientes))
            {
                require('query_instalar_local.php');
            }else
            {
                echo "Cliente não existente na base de dados";
            }

        }
    }
?>

</body>

</html>
