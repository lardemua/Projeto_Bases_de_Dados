<?php

$query = "SELECT cl_ID, cl_nome, cl_morada, cl_IP, cl_port FROM clientes";

$response = @mysqli_query($dbc4,$query);

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

        $query = "INSERT IGNORE clientes VALUES
        (" . $cl_ID . ",\"" .$cl_nome . "\",\"" .$cl_morada . "\",\"" .$cl_IP . "\"," .$cl_port . ")";

        if (!mysqli_query($dbc,$query))
        {
            die("Erro: " . mysqli_error($dbc) . "<br>");
        }

        if (!mysqli_query($dbc2,$query))
        {
            die("Erro: " . mysqli_error($dbc2) . "<br>");
        }

    }

} else{
    echo "Error";

    echo mysqli_error($dbc4);
}

$query = "SELECT m_IDCliente, m_ID, m_nome, m_descricao FROM moldes";

$response = @mysqli_query($dbc4,$query);

if($response)
{
    while($row = mysqli_fetch_array($response))
    {
        if(is_null($row['m_nome']))
        {
            $row['m_nome'] = "NULL";
        }
        if(is_null($row['m_descricao']))
        {
            $row['m_descricao'] = "NULL";
        }
        
        $m_IDCliente = $row['m_IDCliente'];
        $m_ID = $row['m_ID'];
        $m_nome = $row['m_nome'];
        $m_descricao = $row['m_descricao'];

        $query = "INSERT INTO moldes VALUES
        (" . $m_IDCliente . "," .$m_ID . ",\"" .$m_nome . "\",\"" .$m_descricao . "\")";

        if (!mysqli_query($dbc,$query))
        {
            die("Erro: " . mysqli_error($dbc) . "<br>");
        }

        if (!mysqli_query($dbc2,$query))
        {
            die("Erro: " . mysqli_error($dbc2) . "<br>");
        }

    }

} else{
    echo "Error";

    echo mysqli_error($dbc4);
}

$query = "SELECT s_IDMolde, s_num, s_tipo, s_nome, s_descricao FROM sensores";

$response = @mysqli_query($dbc4,$query);

if($response)
{
    while($row = mysqli_fetch_array($response))
    {
        if(is_null($row['s_nome']))
        {
            $row['s_nome'] = "NULL";
        }
        if(is_null($row['s_descricao']))
        {
            $row['s_descricao'] = "NULL";
        }
        
        $s_IDMolde = $row['s_IDMolde'];
        $s_num = $row['s_num'];
        $s_tipo = $row['s_tipo'];
        $s_nome = $row['s_nome'];
        $s_descricao = $row['s_descricao'];

        $query = "INSERT INTO sensores VALUES
        (" . $s_IDMolde . "," . $s_num . "," . $s_tipo . ",\"" . $s_nome . "\",\"" . $s_descricao . "\")";

        if (!mysqli_query($dbc,$query))
        {
            die("Erro: " . mysqli_error($dbc) . "<br>");
        }

        if (!mysqli_query($dbc2,$query))
        {
            die("Erro: " . mysqli_error($dbc2) . "<br>");
        }
    }

} else{
    echo "Error";

    echo mysqli_error($dbc4);
}


        $query = "DELETE FROM sensores";

        if (!mysqli_query($dbc4,$query))
        {
            echo("Erro: " . mysqli_error($dbc4) . "<br>");
        }else
        {
        //echo "sensor eliminado";
        }

        $query = "DELETE FROM moldes";

        if (!mysqli_query($dbc4,$query))
        {
            echo("Erro: " . mysqli_error($dbc4) . "<br>");
        }else
        {
        //echo "sensor eliminado";
        }

?>