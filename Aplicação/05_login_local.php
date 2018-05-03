<?php
session_start();
?>
<html lang="pt">

<head>
    <meta charset="UTF-8" />
    <title>Login</title>
</head>

<body style="background-color:azure;">

    <h1>Aplicação</h1>
    <form action="index.php" style="float: left;">
        <input type="submit" value="Home">
    </form>
    <form action="02_consultas.html" style="float: left;">
        <input type="submit" value="Consultas">
    </form>
    <form action="03_administracao.php" style="float: left;">
        <input type="submit" value="Administração">
    </form>
    <form action="05_login_local.php" style="float: left;">
        <input type="submit" value="Conectar Local">
    </form>
    <form action="04_login.php">
        <input type="submit" value="<?php echo $_SESSION['central_status']; ?>">
    </form>


<?php
    require('query_databases.php');
?>

<form action="05_login_local.php" method="post">
    <table>
        <tr>
        <td>Database Name:</td>
        <td>
            <input type="text" name="database" value="">
        </td>
    </tr>
    </table>
    <input type="submit" name="Select_Database" value="Conectar">
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
            mysqli_close($dbc2);
        }
    }

?>

</body>

</html>