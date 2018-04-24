<html lang="pt">
<head>
    <meta charset="UTF-8" />
    <title>Administração</title>
</head>
<body>

    <h1>Aplicação</h1>
    <form action="index.html" style="float: left;">
        <input type="submit" value="Home">
    </form>
    <form action="02_consultas.html" style="float: left;">
        <input type="submit" value="Consultas">
    </form>
    <form style="float: left;">
        <input type="submit" value="Administração">
    </form>
    <form action="04_login.html" style="float: left;">
        <input type="submit" value="Registar/Login">
    </form>
    <br>
    <br>
    <br>

    <form action="/03_administration.php" method="post" style="float: left;">
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
        <input type="submit" name="Adicionar_Cliente" value="Adicionar Cliente" style="float: right;">
    </form>
    <form action="/create_client.php" style="float: left;">
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
        <input type="submit" value="Adicionar_Molde" style="float: right;">
    </form>
    <form action="/create_client.php" style="float: left;">
        <legend>Sensor:</legend>
        <table>
            <tr>
                <td>ID Molde:</td>
                <td>
                    <input type="text" name="s_molde" value="">
                </td>
            </tr>
            <tr>
                <td>Número do Sensor:</td>
                <td>
                    <input type="text" name="s_sensor" value="">
                </td>
            </tr>
            <tr>
                <td> Nome:</td>
                <td>
                    <input type="text" name="s_nome" value="">
                </td>
            </tr>
            <tr>
                <td> Tipo:</td>
                <td>
                    <select name="s_tipo">
                        <option value="termometro">Termómetro</option>
                        <option value="dinamometro">Dinamómetro</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td valign="top"> Descrição: </td>
                <td>
                    <textarea name="s_descricao" rows="5" cols="33" maxlength="200"></textarea>
                </td>
            </tr>
        </table>
        <input type="submit" value="Adicionar_Sensor" style="float: right;">
    </form>

<?php
    if(isset($_POST['Adicionar_Cliente']))
    {
        $data_missing = array();

        if(empty($_POST['cl_id']))
        {
            $data_missing[] = 'ID Cliente';
        }else{
            $cl_id = trim($POST['cl_id']);
        }

        if(empty($_POST['cl_nome']))
        {
            $data_missing[] = 'Nome Cliente';
        }else{
            $cl_nome = trim($POST['cl_nome']);
        }

        if(empty($_POST['cl_morada']))
        {
            $cl_morada = "NULL";
        }else{
            $cl_morada = trim($POST['cl_morada']);
        }

        if(empty($_POST['cl_ip']))
        {
            $data_missing[] = 'IP Cliente';
        }else{
            $cl_ip = trim($POST['cl_ip']);
        }

        if(empty($_POST['cl_port']))
        {
            $data_missing[] = 'Port Cliente';
        }else{
            $cl_port = trim($POST['cl_port']);
        }

        if(empty($data_missing))
        {
            require_once('central_connect.php');

            $query = "INSERT INTO Clientes VALUES (?,?,?,INET_ATON(?),?)";

            $statement = mysqli_prepare($dbc,$query);

            mysqli_stmt_bind_param($statement,"isssi",$cl_id,$cl_nome,$cl_morada,$cl_ip,$cl_port);

            mysqli_stmt_execute($statement);

            $affected_rows = mysqli_stmt_affected_rows($statement);

            echo $affected_rows;
            mysqli_stmt_close($stmt);
            mysqli_close($dbc);

        }else
        {
            echo "Faltam os seguintes campos <br/>";
            foreach($data_missing as $missing)
            {
                echo "$missing<br/>";
            }
        }
    }
?>
</body>
</html>