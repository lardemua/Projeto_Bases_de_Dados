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
require('local_connect.php');

$query = "SELECT m_IDCliente, m_ID, m_nome, m_descricao, COUNT(DISTINCT s_IDMolde, s_num) FROM clientes INNER JOIN moldes ON cl_ID = m_IDCliente LEFT OUTER JOIN sensores ON m_ID = s_IDMolde GROUP BY m_ID ORDER BY m_IDCliente, m_ID";

$response = @mysqli_query($dbc2,$query);

if($response)
{
    echo '<table class = "query" table_align = "left" cellspacing = "5" cellpadding = "8">

    <tr>
        <td align="left"><b>ID Cliente</b></td>
        <td align="left"><b>ID Molde</b></td>
        <td align="left"><b>Nome</b></td>
        <td align="left"><b>Descrição</b></td>
    </tr>';
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
        echo '<tr>
        <td align="left">' . $row['m_IDCliente'] . '</td>
        <td align="left">' . $row['m_ID'] . '</td>
        <td align="left">' . $row['m_nome'] . '</td>
        <td align="left">' . $row['m_descricao'] . '</td>
        <td align="left">' . $row['COUNT(DISTINCT s_IDMolde, s_num)'] . '</td>
        </tr>';
    }
    echo '</table>';

} else{
    echo "Error";

    echo mysqli_error($dbc2);
}

mysqli_close($dbc2);

?>
</body>
</html>