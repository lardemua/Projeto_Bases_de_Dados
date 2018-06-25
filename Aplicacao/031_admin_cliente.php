<?php
session_start();
?>
<html lang="pt">
<head>
    <meta charset="UTF-8" />
    <title>Administração</title>
</head>
<body style="background-color:azure;">
<?php
    if($_SESSION['central_status'] != "Logout")
        {
            ob_start(); // ensures anything dumped out will be caught
            $url = 'index.php'; // this can be set based on whatever

            // clear out the output buffer
            while (ob_get_status()) 
            {
                ob_end_clean();
            }

            // no redirect
            header( "Location: $url" );

        }
?>

    <h1>Aplicação</h1>
    <form action="index.php" style="float: left;">
        <input type="submit" value="Home">
    </form>
    <form action="02_consultas.php" style="float: left;">
        <input type="submit" value="Consultas">
    </form>
    <form action="03_administracao.php" style="float: left;">
        <input type="submit" value="Administração Local">
    </form>
    <form action="04_login.php">
        <input type="submit" value="<?php echo $_SESSION['central_status']; ?>">
    </form>
    <?php
        if($_SESSION['central_status'] == "Logout" && $_SESSION['local_status'] == "Disconnect")
        {
	echo "<form action=\"031_admin_cliente.php\" style=\"float: left;\">
            <input type=\"submit\" value=\"Administração\">
            </form>";
            echo "<form action=\"05_login_local.php\">
            <input type=\"submit\" value=\"" . $_SESSION['local_name'] . "\">
            </form>";
        }else if($_SESSION['central_status'] == "Logout" && $_SESSION['local_status'] != "Disconnect")
        {
	echo "<form action=\"031_admin_cliente.php\" style=\"float: left;\">
            <input type=\"submit\" value=\"Administração\" disabled>
            </form>";
            echo "<form action=\"05_login_local.php\">
            <input type=\"submit\" value=\"Conectar Local\">
            </form>";
        }
    ?>
    <form action="031_admin_cliente.php" style="float: left;">
        <input type="submit" value="Gestão Clientes">
    </form>
    <form action="032_admin_molde.php" style="float: left;">
        <input type="submit" value="Gestão Moldes">
    </form>
    <form action="033_admin_sensor.php">
        <input type="submit" value="Gestão Sensores">
    </form>

    <?php
	/*
        if($_SESSION['central_status'] == "Logout" && $_SESSION['local_status'] == "Disconnect")
        {
            echo     "<form action=\"031_admin_cliente.php\" style=\"float: left;\">
                    <input type=\"submit\" value=\"Gestão Clientes\">
                    </form>
                    <form action=\"032_admin_molde.php\" style=\"float: left;\">
                    <input type=\"submit\" value=\"Gestão Moldes\">
                    </form>
                    <form action=\"033_admin_sensor.php\">
                    <input type=\"submit\" value=\"Gestão Sensores\">
                    </form>
                    <!-- <form action=\"034_admin_eliminar.php\">
                    <input type=\"submit\" value=\"Eliminar\">
                    </form> -->";

        }else
        {  
            echo "<form action=\"031_admin_cliente.php\">
            <input type=\"submit\" value=\"Gestão Clientes\">
            </form>";
        }
	*/
    ?>


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
        <input type="submit" name="Alterar_Cliente" value="Alterar Cliente">
        <input type="submit" name="Eliminar_Cliente" value="Eliminar Cliente">
	<input type="submit" name="Atualizar_Cliente" value="Atualizar">
        <br>
        <br>
        <input type="submit" name="Ver_Cliente" value="Ver Clientes">
        <?php
            if($_SESSION['central_status'] == "Logout" && $_SESSION['local_status'] == "Disconnect")
            {
                echo "
            <input type=\"submit\" name=\"Ver_Molde\" value=\"Ver Moldes\">
            <input type=\"submit\" name=\"Ver_Sensor\" value=\"Ver Sensores\">";
            }else
            {

            }
        ?>
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

    if(isset($_POST['Alterar_Cliente']))
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

            $query = "UPDATE clientes SET cl_nome = ?, cl_morada = ?, cl_ip = ?, cl_port = ? WHERE cl_id = ?";

            $stmt = mysqli_prepare($dbc,$query);

            mysqli_stmt_bind_param($stmt, "sssii", $cl_nome, $cl_morada, $cl_ip, $cl_port, $cl_id);

            mysqli_stmt_execute($stmt);

            $affected_rows = mysqli_stmt_affected_rows($stmt);

            if($affected_rows == 1)
            {
                echo "Cliente alterado com sucesso";
            }else
            {
                echo "Erro: " . mysqli_error($dbc);
            }
            mysqli_stmt_close($stmt);
            mysqli_close($dbc);
        }
    }

    if(isset($_POST['Eliminar_Cliente']))
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

            $query = "DELETE FROM clientes WHERE cl_id = ?";

            $stmt = mysqli_prepare($dbc,$query);

            mysqli_stmt_bind_param($stmt, "i", $cl_id);

            mysqli_stmt_execute($stmt);

            $affected_rows = mysqli_stmt_affected_rows($stmt);

            if($affected_rows == 1)
            {
                echo "Cliente eliminado com sucesso";
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
