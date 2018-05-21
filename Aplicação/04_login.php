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
        if($_SESSION['central_status'] == "Logout")
        {
            // remove all session variables
            session_unset(); 
        }
    ?>

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

    <form action="/04_login.php" method="post">
        <fieldset style="float: left;">
            <legend>Login:</legend>
            Utilizador:
            <br>
            <input type="text" name="user" value="">
            <br> Password:
            <br>
            <input type="password" name="pass" value="">
            <br>
            <br>
            <input type="submit" name="Login_Central" value="Login">
        </fieldset>
    </form>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>

<!--
    <p>
        Não tem conta?
        <a href="05_registar.html">Registar</a>
    </p>
-->

    <?php
    if(isset($_POST['Login_Central']))
    {
        $data_missing = array();
        $go = 0;

        if(empty($_POST['user']))
        {
            $data_missing[] = 'Utilizador';
        }else{
            $_SESSION["user"] = trim($_POST['user']);
        }

        if(empty($_POST['pass']))
        {
            $data_missing[] = 'Password';
        }else{
            $_SESSION['password'] = trim($_POST['pass']);
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
            require('central_connect.php');
            mysqli_close($dbc);
            
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
    }
    ?>

</body>

</html>
