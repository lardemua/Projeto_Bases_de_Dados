<?php
session_start();
?>
<?php
    DEFINE('DB_USER',$_SESSION['user']);
    DEFINE('DB_PASSWORD',$_SESSION['password']);
    DEFINE('DB_HOST','127.0.0.1');
    DEFINE('DB_NAME','central');

    $dbc = @mysqli_connect(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME);

    // Check connection
    if (!$dbc) {
        $_SESSION['central_status'] = "Login";
        die('Could not connect to MySQL ' . mysqli_connect_error());
    }else {
        $_SESSION['central_status'] = "Logout";
    }

    // Change character set to utf8
    mysqli_set_charset($dbc,"utf8");
?>