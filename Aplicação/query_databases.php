<html lang="pt">
<head>
    <meta charset="UTF-8" />
    <title>Administração</title>
    <style>
    table.query tr:nth-child(even) {
        background-color: #dddddd;
    }
</style>
</head>
<body>
<?php
    DEFINE('DB_USER_Local',$_SESSION['user']);
    DEFINE('DB_PASSWORD_Local',$_SESSION['password']);
    DEFINE('DB_HOST_Local','localhost');

    $dbc2 = @mysqli_connect(DB_HOST_Local,DB_USER_Local,DB_PASSWORD_Local);

    // Check connection
    if (!$dbc2) {
        die('Could not connect to MySQL ' . mysqli_connect_error());
    }else {
    }

    // Change character set to utf8
    mysqli_set_charset($dbc2,"utf8");

    $query = "SHOW DATABASES";

    $response = @mysqli_query($dbc2,$query);

    if($response)
    {
        $db_names = array();

        while($row = mysqli_fetch_array($response))
        {
            if (strpos($row['Database'], 'cl_') !== false)
            {
                $db_names[] = $row['Database'];
            }
        }

        if(empty($db_names))
        {
            echo "Não foram detetadas bases de dados locais.";
        }else
        {
            echo '<table class = "query" table_align = "left" cellspacing = "5" cellpadding = "8">

            <tr>
                <td align="left"><b>Database Name</b></td>
            </tr>';
            foreach($db_names as $name)
            {
                echo '<tr>
                        <td align="left">' . $name . '</td>
                    </tr>';
            }
            echo '</table>';
        }

    } else{
        echo "Error";

        echo mysqli_error($dbc2);
    }

    mysqli_close($dbc2);

?>
</body>
</html>