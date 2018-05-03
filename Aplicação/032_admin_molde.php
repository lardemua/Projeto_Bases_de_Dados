<?php
session_start();
?>
<html lang="pt">
<head>
    <meta charset="UTF-8" />
    <title>Administração</title>
</head>
<body style="background-color:azure;">

    <h1>Aplicação</h1>
    <form action="index.php" style="float: left;">
        <input type="submit" value="Home">
    </form>
    <form action="02_consultas.html" style="float: left;">
        <input type="submit" value="Consultas">
    </form>
    <form action="03_administracao.php" style="float: left;">
        <input type="submit" value="Administração">
    </form>
    <form action="05_login_local.php" style="float: left;">
        <input type="submit" value="Conectar Local">
    </form>
    <form action="04_login.php">
        <input type="submit" value="<?php echo $_SESSION['central_status']; ?>">
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

    <form action="032_admin_molde.php" method = "post">
        <legend>Molde:</legend>
        <table>
            <tr>
                <td>ID Cliente:</td>
                <td>
                    <input type="text" name="m_cliente" value="">
                </td>
            </tr>
            <tr>
                <td>ID Molde:</td>
                <td>
                    <input type="text" name="m_molde" value="">
                </td>
            </tr>
            <tr>
                <td> Nome:</td>
                <td>
                    <input type="text" name="m_nome" value="">
                </td>
            </tr>
            <tr>
                <td valign="top"> Descrição: </td>
                <td>
                    <textarea name="m_descricao" rows="5" cols="33" maxlength="200"></textarea>
                </td>
            </tr>
        </table>
        <input type="submit" name="Adicionar_Molde" value="Adicionar Molde">
        <br>
        <br>
        <input type="submit" name="Ver_Cliente" value="Ver Clientes">
        <input type="submit" name="Ver_Molde" value="Ver Moldes">
        <input type="submit" name="Ver_Sensor" value="Ver Sensores">
    </form>

<?php
    if(isset($_POST['Adicionar_Molde']))
    {
        $data_missing = array();
        $data_wrong = array();
        $go = 0;

        if(empty($_POST['m_cliente']))
        {
            $data_missing[] = 'ID Cliente';
        }else{
            $m_cliente = trim($_POST['m_cliente']);
        }
        if(!is_numeric($m_cliente))
        {
            $data_wrong[] = 'ID Cliente';
        }

        if(empty($_POST['m_molde']))
        {
            $data_missing[] = 'ID Molde';
        }else{
            $m_molde = trim($_POST['m_molde']);
        }
        if(!is_numeric($m_molde))
        {
            $data_wrong[] = 'ID Molde';
        }

        if(empty($_POST['m_nome']))
        {
            $m_nome = "NULL";
        }else{
            $m_nome = trim($_POST['m_nome']);
        }

        if(empty($_POST['m_descricao']))
        {
            $m_descricao = "NULL";
        }else{
            $m_descricao = trim($_POST['m_descricao']);
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

            $query = "INSERT INTO moldes VALUES (?,?,?,?)";

            $stmt = mysqli_prepare($dbc,$query);

            mysqli_stmt_bind_param($stmt, "iiss", $m_cliente, $m_molde, $m_nome, $m_descricao);

            mysqli_stmt_execute($stmt);

            $affected_rows = mysqli_stmt_affected_rows($stmt);

            if($affected_rows == 1)
            {
                echo "Molde criado com sucesso";
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
        require('query_molde.php');
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