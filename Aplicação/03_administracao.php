<html lang="pt">
<head>
    <meta charset="UTF-8" />
    <title>Administração</title>
</head>
<body style="background-color:azure;">

    <h1>Aplicação</h1>
    <form action="index.html" style="float: left;">
        <input type="submit" value="Home">
    </form>
    <form action="02_consultas.html" style="float: left;">
        <input type="submit" value="Consultas">
    </form>
    <form action="03_administracao.php" style="float: left;">
        <input type="submit" value="Administração">
    </form>
    <form action="04_login.html">
        <input type="submit" value="Login">
    </form>
    <form action="031_admin_cliente.php" style="float: left;">
        <input type="submit" value="Clientes">
    </form>
    <form action="032_admin_molde.php" style="float: left;">
        <input type="submit" value="Moldes">
    </form>
    <form action="033_admin_sensor.php">
        <input type="submit" value="Sensores">
    </form>

<?php
    require('query.php');
?>

</body>
</html>