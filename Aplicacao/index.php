<?php
session_start();
?>
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
        echo "<form action=\"02_consultas.php\" style=\"float: left;\">
        <input type=\"submit\" value=\"Consultas\">
            </form>
            <form action=\"03_administracao.php\" style=\"float: left;\">
            <input type=\"submit\" value=\"Administração\">
            </form>";
    }else
    {
        $_SESSION['central_status'] = "Login";
    }
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
    <form action="04_login.php" style="float: left;">
        <input type="submit" value="<?php echo $_SESSION['central_status']; ?>">
    </form>
    <br>
    <br>
    <br>

    <img src="logo_ua.png" alt="logo ua" style="height:15%">

    <p>Aplicação experimental em PHP e HTML para utilizar a base de dados.</p>

    <?php


    if($_SESSION['central_status'] == "Logout")
    {
        echo "<p>A área de Consultas assiste na geração queries para consultar a base de dados central.<br>
		A área de Administração permite gerir a informação dos clientes, moldes e sensores.<br>
		A área de Conectar Local acede informações no sistema local do cliente.";
    }

/*
        $ID = str_replace("cl_"," ",$_SESSION['local_name']);
        echo $ID;
        echo "<br>";
         echo "\$_SERVER['PHP_SELF'] => " . $_SERVER['PHP_SELF'];
         echo "<br>";
         echo "\$_SERVER['GATEWAY_INTERFACE'] => " . $_SERVER['GATEWAY_INTERFACE'];
         echo "<br>";
         echo "\$_SERVER['SERVER_ADDR'] => " . $_SERVER['SERVER_ADDR'];
         echo "<br>";
         echo "\$_SERVER['SERVER_NAME'] => " . $_SERVER['SERVER_NAME'];
         echo "<br>";
         echo "\$_SERVER['SERVER_SOFTWARE'] => " . $_SERVER['SERVER_SOFTWARE'];
         echo "<br>";
         echo "\$_SERVER['SERVER_PROTOCOL'] => " . $_SERVER['SERVER_PROTOCOL'];
         echo "<br>";
         echo "\$_SERVER['REQUEST_METHOD'] => " . $_SERVER['REQUEST_METHOD'];
         echo "<br>";
         echo "\$_SERVER['REQUEST_TIME'] => " . $_SERVER['REQUEST_TIME'];
         echo "<br>";
         echo "\$_SERVER['QUERY_STRING'] => " . $_SERVER['QUERY_STRING'];
         echo "<br>";
         echo "\$_SERVER['HTTP_ACCEPT'] => " . $_SERVER['HTTP_ACCEPT'];
         echo "<br>";
         echo "\$_SERVER['HTTP_ACCEPT_CHARSET'] => " . $_SERVER['HTTP_ACCEPT_CHARSET'];
         echo "<br>";
         echo "\$_SERVER['HTTP_HOST'] => " . $_SERVER['HTTP_HOST'];
         echo "<br>";
         echo "\$_SERVER['HTTP_REFERER'] => " . $_SERVER['HTTP_REFERER'];
         echo "<br>";
         echo "\$_SERVER['HTTPS'] => " . $_SERVER['HTTPS'];
         echo "<br>";
         echo "\$_SERVER['REMOTE_ADDR'] => " . $_SERVER['REMOTE_ADDR'];
         echo "<br>";
         echo "\$_SERVER['REMOTE_HOST'] => " . $_SERVER['REMOTE_HOST'];
         echo "<br>";
         echo "\$_SERVER['REMOTE_PORT'] => " . $_SERVER['REMOTE_PORT'];
         echo "<br>";
         echo "\$_SERVER['SCRIPT_FILENAME'] => " . $_SERVER['SCRIPT_FILENAME'];
         echo "<br>";
         echo "\$_SERVER['SERVER_ADMIN'] => " . $_SERVER['SERVER_ADMIN'];
         echo "<br>";
         echo "\$_SERVER['SERVER_PORT'] => " . $_SERVER['SERVER_PORT'];
         echo "<br>";
         echo "\$_SERVER['SERVER_SIGNATURE'] => " . $_SERVER['SERVER_SIGNATURE'];
         echo "<br>";
         echo "\$_SERVER['PATH_TRANSLATED'] => " . $_SERVER['PATH_TRANSLATED'];
         echo "<br>";
         echo "\$_SERVER['SCRIPT_NAME'] => " . $_SERVER['SCRIPT_NAME'];
         echo "<br>";
         echo "\$_SERVER['SCRIPT_URI'] => " . $_SERVER['SCRIPT_URI'];

         /*
            $_SERVER['PHP_SELF']
            $_SERVER['GATEWAY_INTERFACE']
            $_SERVER['SERVER_ADDR']
            $_SERVER['SERVER_NAME']
            $_SERVER['SERVER_SOFTWARE']
            $_SERVER['SERVER_PROTOCOL']
            $_SERVER['REQUEST_METHOD']
            $_SERVER['REQUEST_TIME']
            $_SERVER['QUERY_STRING']
            $_SERVER['HTTP_ACCEPT']
            $_SERVER['HTTP_ACCEPT_CHARSET']
            $_SERVER['HTTP_HOST']
            $_SERVER['HTTP_REFERER']
            $_SERVER['HTTPS']
            $_SERVER['REMOTE_ADDR']
            $_SERVER['REMOTE_HOST']
            $_SERVER['REMOTE_PORT']
            $_SERVER['SCRIPT_FILENAME']
            $_SERVER['SERVER_ADMIN']
            $_SERVER['SERVER_PORT']
            $_SERVER['SERVER_SIGNATURE']
            $_SERVER['PATH_TRANSLATED']
            $_SERVER['SCRIPT_NAME']
            $_SERVER['SCRIPT_URI']
         */

    ?>

</body>

</html>
