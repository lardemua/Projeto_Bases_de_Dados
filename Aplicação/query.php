<?php
require_once('central_connect.php');

$query = "SELECT ID_cliente, nome, morada, INET_NTOA(IP), port FROM Clientes";

$response = @mysqli_query($dbc,$query);

if($response)
{
    echo '<table table_align = "left" cellspacing = "5" cellpadding = "8">

    <tr>
        <td align="left"><b>ID</b></td>
        <td align="left"><b>Nome</b></td>
        <td align="left"><b>Morada</b></td>
        <td align="left"><b>IP</b></td>
        <td align="left"><b>Port</b></td>
    </tr>';
    while($row = mysqli_fetch_array($response))
    {
        if(is_null($result['morada']))
        {
            $row['morada'] = "NULL";
        }
        echo '<tr>
        <td align="left">' . $row['ID_cliente'] . '</td>
        <td align="left">' . $row['nome'] . '</td>
        <td align="left">' . $row['morada'] . '</td>
        <td align="left">' . $row['INET_NTOA(IP)'] . '</td>
        <td align="left">' . $row['port'] . '</td>
        </tr>';
    }
    echo '</table>';

} else{
    echo "Error";

    echo mysqli_error($dbc);
}

mysqli_close($dbc);

?>