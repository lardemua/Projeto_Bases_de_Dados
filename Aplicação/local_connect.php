<?php
session_start();
?>
<?php
    DEFINE('DB_USER_Local',$_SESSION['user']);
    DEFINE('DB_PASSWORD_Local',$_SESSION['password']);
    DEFINE('DB_HOST_Local','localhost');
    DEFINE('DB_NAME_Local',$_SESSION['local_name']);

    $dbc2 = @mysqli_connect(DB_HOST_Local,DB_USER_Local,DB_PASSWORD_Local,DB_NAME_Local);

    // Check connection
    if (!$dbc2) {
        $_SESSION['local_status'] = "Connect";
        die('Could not connect to MySQL ' . mysqli_connect_error());
    }else {
        $_SESSION['local_status'] = "Disconnect";
    }

    // Change character set to utf8
    mysqli_set_charset($dbc2,"utf8");
?>