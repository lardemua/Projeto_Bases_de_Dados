<?php
    DEFINE('DB_USER','sapofree');
    DEFINE('DB_PASSWORD','naopossodizer');
    DEFINE('DB_HOST','127.0.0.1');
    DEFINE('DB_NAME','central');

    $dbc = @mysqli_connect(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME)
    OR die('Could not connect to MySQL ' . mysqli_connect_error());
?>
