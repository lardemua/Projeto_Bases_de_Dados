<?php
session_start();
echo "status is: " . $_SESSION['central_status'];
?>
<! DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8" />
    <title>Main</title>
</head>

<body style="background-color:azure;">

    <h1>Aplicação</h1>

    <form style="float: left;">
        <input type="submit" value="Home">
    </form>
    <?php
    if($_SESSION['central_status'] == "Logout")
    {
        echo "<form action=\"02_consultas.html\" style=\"float: left;\">
        <input type=\"submit\" value=\"Consultas\">
            </form>
            <form action=\"03_administracao.php\" style=\"float: left;\">
            <input type=\"submit\" value=\"Administração\">
            </form>";
    }else
    {
        $_SESSION['central_status'] = "Login";
    }
    ?>
    <form action="04_login.php" style="float: left;">
        <input type="submit" value="<?php echo $_SESSION['central_status']; ?>">
    </form>
    <form action="05_login_local.php" style="float: left;">
        <input type="submit" value="Connect">
    </form>
    <br>
    <br>
    <br>

    <img src="logo_ua.png" alt="logo ua" style="height:15%">

    <p>Aplicação experimental em PHP e HTML para utilizar a base de dados</p>

</body>

</html>