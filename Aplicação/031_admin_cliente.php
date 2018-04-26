<html lang="pt">
<head>
    <meta charset="UTF-8" />
    <title>Administração</title>
</head>
<body style="background-color:azure;">

    <h1>Aplicação</h1>
    <form action="index.html" style="float: left;">
        <input type="submit" value="Home">
    </form>
    <form action="02_consultas.html" style="float: left;">
        <input type="submit" value="Consultas">
    </form>
    <form action="03_administracao.php" style="float: left;">
        <input type="submit" value="Administração">
    </form>
    <form action="04_login.html">
        <input type="submit" value="Login">
    </form>
    <form action="031_admin_cliente.php" style="float: left;">
        <input type="submit" value="Clientes">
    </form>
    <form action="032_admin_molde.php" style="float: left;">
        <input type="submit" value="Moldes">
    </form>
    <form action="033_admin_sensor.php">
        <input type="submit" value="Sensores">
    </form>

    <form action="031_admin_cliente.php" method="post">
        <legend>Cliente:</legend>
        <table>
            <tr>
                <td>ID:</td>
                <td>
                    <input type="text" name="cl_id" value="">
                </td>
            </tr>
            <tr>
                <td>Nome:</td>
                <td>
                    <input type="text" name="cl_nome" value="">
                </td>
            </tr>
            <tr>
                <td> Morada:</td>
                <td>
                    <input type="text" name="cl_morada" value="">
                </td>
            </tr>
            <tr>
                <td> IP: </td>
                <td>
                    <input type="text" name="cl_ip" value="">
                </td>
            </tr>
            <tr>
                <td>Port:</td>
                <td>
                    <input type="text" name="cl_port" value="">
                </td>
            </tr>
        </table>
        <input type="submit" name="Adicionar_Cliente" value="Adicionar Cliente">
        <br>
        <br>
        <input type="submit" name="Ver_Cliente" value="Ver Clientes">
        <input type="submit" name="Ver_Molde" value="Ver Moldes">
        <input type="submit" name="Ver_Sensor" value="Ver Sensores">
    </form>

<?php
    if(isset($_POST['Adicionar_Cliente']))
    {
        $data_missing = array();
        $data_wrong = array();
        $go = 0;

        if(empty($_POST['cl_id']))
        {
            $data_missing[] = 'ID Cliente';
        }else{
            $cl_id = trim($_POST['cl_id']);
        }
        if(!is_numeric($cl_id))
        {
            $data_wrong[] = 'ID Cliente';
        }

        if(empty($_POST['cl_nome']))
        {
            $data_missing[] = 'Nome Cliente';
        }else{
            $cl_nome = trim($_POST['cl_nome']);
        }

        if(empty($_POST['cl_morada']))
        {
            $cl_morada = "NULL";
        }else{
            $cl_morada = trim($_POST['cl_morada']);
        }

        if(empty($_POST['cl_ip']))
        {
            $data_missing[] = 'IP Cliente';
        }else{
            $cl_ip = trim($_POST['cl_ip']);
        }

        if(empty($_POST['cl_port']))
        {
            $data_missing[] = 'Port Cliente';
        }else{
            $cl_port = trim($_POST['cl_port']);
        }
        if(!is_numeric($cl_port))
        {
            $data_wrong[] = 'Port Cliente';
        }

        if(empty($data_missing))
        {
            if(empty($data_wrong))
            {
                $go = 1;
            }else
            {
                echo "Não são aceites os seguintes campos: <br/>";
                foreach($data_wrong as $wrong)
                {
                    echo "$wrong <br/>";
                }
                $go = 0;
            }
        }else
        {
            echo "Faltam os seguintes campos: <br/>";
            foreach($data_missing as $missing)
            {
                echo "$missing<br/>";
            }
            $go = 0;
        }
        if($go)
        {
            require('central_connect.php');

            $query = "INSERT INTO clientes VALUES (?,?,?,?,?)";

            $stmt = mysqli_prepare($dbc,$query);

            mysqli_stmt_bind_param($stmt, "isssi", $cl_id, $cl_nome, $cl_morada, $cl_ip, $cl_port);

            mysqli_stmt_execute($stmt);

            $affected_rows = mysqli_stmt_affected_rows($stmt);

            if($affected_rows == 1)
            {
                echo "Cliente criado com sucesso";
            }else
            {
                echo "Erro: " . mysqli_error($dbc);
            }
            mysqli_stmt_close($stmt);
            mysqli_close($dbc);
        }
    }

    if(!isset($_POST['Ver_Cliente']) && !isset($_POST['Ver_Molde']) && !isset($_POST['Ver_Sensor']))
    {
        require('query_cliente.php');
    }

    if(isset($_POST['Ver_Cliente']))
    {
        require('query_cliente.php');
    }

    if(isset($_POST['Ver_Molde']))
    {
        require('query_molde.php');
    }

    if(isset($_POST['Ver_Sensor']))
    {
        require('query_sensor.php');
    }

?>

</body>
</html>