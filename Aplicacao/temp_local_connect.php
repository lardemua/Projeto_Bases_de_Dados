<?php
session_start();
?>
<?php
    $DB_USER_Local=$_SESSION['user'];
    $DB_PASSWORD_Local=$_SESSION['password'];
    $DB_HOST_Local=$_SERVER['REMOTE_ADDR'];
    $DB_NAME_Local='temp_local';

    $dbc4 = @mysqli_connect($DB_HOST_Local,$DB_USER_Local,$DB_PASSWORD_Local,$DB_NAME_Local);

    // Check connection
    if (!$dbc4) {
        die('Could not connect to MySQL ' . mysqli_connect_error());
    }else {
    }

    // Change character set to utf8
    mysqli_set_charset($dbc4,"utf8");
?>