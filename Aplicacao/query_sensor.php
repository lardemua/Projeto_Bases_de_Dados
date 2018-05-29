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

$query = "SELECT m_IDCliente, s_IDMolde, s_num, tipo_nome, s_nome, s_descricao FROM moldes INNER JOIN sensores ON m_ID = s_IDMolde INNER JOIN tipo ON s_tipo = tipo_id ORDER BY m_IDCliente, s_IDMolde, s_num";

$response = @mysqli_query($dbc2,$query);

if($response)
{
    echo '<table class = "query" table_align = "left" cellspacing = "5" cellpadding = "8">

    <tr>
        <td align="left"><b>ID Cliente</b></td>
        <td align="left"><b>ID Molde</b></td>
        <td align="left"><b>Número do Sensor</b></td>
        <td align="left"><b>Tipo de Sensor</b></td>
        <td align="left"><b>Nome</b></td>
        <td align="left"><b>Descrição</b></td>
    </tr>';
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
        echo '<tr>
        <td align="left">' . $row['m_IDCliente'] . '</td>
        <td align="left">' . $row['s_IDMolde'] . '</td>
        <td align="left">' . $row['s_num'] . '</td>
        <td align="left">' . $row['tipo_nome'] . '</td>
        <td align="left">' . $row['s_nome'] . '</td>
        <td align="left">' . $row['s_descricao'] . '</td>
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