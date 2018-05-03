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
    <form action="04_login.php" style="float: left;">
        <input type="submit" value="Login">
    </form>
    <br>
    <br>
    <br>

<?php

    require('query_databases.php');
    ?>

</body>

</html>