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
require('central_connect.php');

$query = "SELECT cl_ID, cl_nome, cl_morada, cl_IP, cl_port FROM (SELECT cl_ID, cl_nome, cl_morada, cl_IP, cl_port, COUNT(DISTINCT m_ID) AS n_moldes FROM clientes LEFT OUTER JOIN moldes ON cl_ID = m_IDCliente GROUP BY cl_ID) AS contagem WHERE n_moldes = 0";

$response = @mysqli_query($dbc,$query);

if($response)
{
    echo '<table class = "query" table_align = "left" cellspacing = "5" cellpadding = "8">

    <tr>
        <td align="left"><b>ID</b></td>
        <td align="left"><b>Nome</b></td>
        <td align="left"><b>Morada</b></td>
        <td align="left"><b>IP</b></td>
        <td align="left"><b>Port</b></td>
    </tr>';
    while($row = mysqli_fetch_array($response))
    {
        if(is_null($row['cl_morada']))
        {
            $row['cl_morada'] = "NULL";
        }
        echo '<tr>
        <td align="left">' . $row['cl_ID'] . '</td>
        <td align="left">' . $row['cl_nome'] . '</td>
        <td align="left">' . $row['cl_morada'] . '</td>
        <td align="left">' . $row['cl_IP'] . '</td>
        <td align="left">' . $row['cl_port'] . '</td>
        </tr>';

        $id_clientes[] = $row['cl_ID'];
    }
    echo '</table>';

} else{
    echo "Error: ";

    echo mysqli_error($dbc);
}

mysqli_close($dbc);

?>
</body>
</html>
