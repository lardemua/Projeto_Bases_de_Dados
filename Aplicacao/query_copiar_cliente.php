<?php

$query = "DELETE FROM clientes";

    if (!mysqli_query($dbc4,$query))
    {
        echo("Erro: " . mysqli_error($dbc4) . "<br>");
    }


$ID = str_replace("cl_"," ",$_SESSION['local_name']);

$query = "SELECT cl_ID, cl_nome, cl_morada, cl_IP, cl_port FROM clientes WHERE cl_ID = " . $ID;

$response = @mysqli_query($dbc,$query);

if($response)
{
    while($row = mysqli_fetch_array($response))
    {
        if(is_null($row['cl_morada']))
        {
            $row['cl_morada'] = "NULL";
        }
        $cl_ID = $row['cl_ID'];
        $cl_nome = $row['cl_nome'];
        $cl_morada = $row['cl_morada'];
        $cl_IP = $row['cl_IP'];
        $cl_port = $row['cl_port'];
    }

} else{
    echo "Error";

    echo mysqli_error($dbc);
}

$query = "INSERT INTO clientes VALUES
        (" . $cl_ID . ",\"" .$cl_nome . "\",\"" .$cl_morada . "\",\"" .$cl_IP . "\"," .$cl_port . ")";

    if (!mysqli_query($dbc4,$query))
    {
        echo("Erro: " . mysqli_error($dbc4) . "<br>");
    }

?>