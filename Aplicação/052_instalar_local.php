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
        <input type="submit" value="Criar">
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
        <td>Database Name:</td>
        <td>
            <input type="text" name="database" value="">
        </td>
    </tr>
    </table>
    <input type="submit" name="Select_Database" value="Conectar" style="float:left;">
    <input type="submit" name="Deselect_Database" value="Desconectar">
</form>

<?php

?>

</body>

</html>