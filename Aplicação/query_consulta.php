<html lang="pt">
<head>
    <meta charset="UTF-8" />
    <title>Consultas</title>
    <style>
    table.query tr:nth-child(even) {
        background-color: #dddddd;
    }
</style>
</head>
<body>
<?php
require('central_connect.php');

switch($queryOption)
{
    case 1:
        $query = "SELECT " . $projecao . " FROM clientes";
        if(!empty($selecao))
        {
            $query .= " WHERE " . $selecao;
        }
        if(!empty($ordem))
        {
            $query .= " ORDER BY " . $ordem;
        }
        break;
    case 2:
        $query = "SELECT " . $projecao . " FROM clientes INNER JOIN moldes ON cl_ID = m_IDCliente";
        if(!empty($selecao))
        {
            $query .= " WHERE " . $selecao;
        }
        if(!empty($ordem))
        {
            $query .= " ORDER BY " . $ordem;
        }
        break;
    case 3:
        $query = "SELECT " . $projecao . " FROM clientes INNER JOIN moldes ON cl_ID = m_IDCliente INNER JOIN sensores ON m_ID = s_IDMolde INNER JOIN tipo ON s_tipo = tipo_ID";
        if(!empty($selecao))
        {
            $query .= " WHERE " . $selecao;
        }
        if(!empty($ordem))
        {
            $query .= " ORDER BY " . $ordem;
        }
        break;
    case 4:
        $query = "SELECT " . $projecao . " FROM clientes INNER JOIN moldes ON cl_ID = m_IDCliente INNER JOIN sensores ON m_ID = s_IDMolde  INNER JOIN tipo ON s_tipo = tipo_ID INNER JOIN registos ON s_IDMolde = r_IDMolde AND s_num = r_numSensor INNER JOIN fase ON r_fase = fase_ID";
        if(!empty($selecao))
        {
            $query .= " WHERE " . $selecao;
        }
        if(!empty($ordem))
        {
            $query .= " ORDER BY " . $ordem;
        }
        break;
    case 5:
        $query = $query_text;
    break;
}

echo "Query anterior: " . $query . "<br>";

$response = @mysqli_query($dbc,$query);

if($response)
{
    echo '<table class = "query" table_align = "left" cellspacing = "5" cellpadding = "8">
    <tr>';
    for ($x = 0; $x < $numeroDeAtributos; $x++)
    {
        echo '<td align="left"><b>' . trim($atributos[$x]) .'</b></td>';
    }
    echo '</tr>';

    while($row = mysqli_fetch_array($response))
    {
        echo '<tr>';
        for ($x = 0; $x < $numeroDeAtributos; $x++)
        {
            if(is_null($row[$x]))
            {
                $row[$x] = "NULL";
            }
            echo '<td align="left">' . $row[$x] .'</td>';
        }
        echo '</tr>';
    }
    echo '</table>';

} else{
    echo "Error";

    echo mysqli_error($dbc);
}

mysqli_close($dbc);

?>
</body>
</html>