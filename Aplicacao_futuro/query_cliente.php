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
if($_SESSION['central_status'] == "Logout" && $_SESSION['local_status'] == "Disconnect")
{
	require('local_connect.php');
}else
{
	require('central_connect.php');
}


$query = "SELECT cl_ID, cl_nome, cl_morada, cl_IP, cl_port, COUNT(DISTINCT m_ID), COUNT(DISTINCT s_IDMolde, s_num) FROM clientes LEFT OUTER JOIN moldes ON cl_ID = m_IDCliente LEFT OUTER JOIN sensores ON m_ID = s_IDMolde GROUP BY cl_ID ORDER BY cl_ID";

if($_SESSION['central_status'] == "Logout" && $_SESSION['local_status'] == "Disconnect")
{
	$response = @mysqli_query($dbc2,$query);
}else
{
	$response = @mysqli_query($dbc,$query);
}

if($response)
{
    echo '<table class = "query" table_align = "left" cellspacing = "5" cellpadding = "8">

    <tr>
        <td align="left"><b>ID</b></td>
        <td align="left"><b>Nome</b></td>
        <td align="left"><b>Morada</b></td>
        <td align="left"><b>IP</b></td>
        <td align="left"><b>Port</b></td>
        <td align="left"><b>Nº de Moldes</b></td>
        <td align="left"><b>Nº de Sensores</b></td>
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
        <td align="left">' . $row['COUNT(DISTINCT m_ID)'] . '</td>
        <td align="left">' . $row['COUNT(DISTINCT s_IDMolde, s_num)'] . '</td>
        </tr>';
    }
    echo '</table>';

} else{
    echo "Error: ";
	if($_SESSION['central_status'] == "Logout" && $_SESSION['local_status'] == "Disconnect")
	{
		echo mysqli_error($dbc2);
	}else
	{
		echo mysqli_error($dbc);
	}
}

if($_SESSION['central_status'] == "Logout" && $_SESSION['local_status'] == "Disconnect")
{
	mysqli_close($dbc2);
}else
{
	mysqli_close($dbc);
}

?>
</body>
</html>
