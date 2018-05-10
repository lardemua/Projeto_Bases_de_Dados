<?php
session_start();
?>
<html lang="pt">
<head>
    <meta charset="UTF-8" />
    <title>Consultas</title>
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
    <form style="float: left;">
        <input type="submit" value="Consultas">
    </form>
    <form action="03_administracao.php" style="float: left;">
        <input type="submit" value="Administração">
    </form>
    <?php
        if($_SESSION['central_status'] == "Logout" && $_SESSION['local_status'] == "Disconnect")
        {
            echo "<form action=\"05_login_local.php\" style=\"float: left;\">
            <input type=\"submit\" value=\"" . $_SESSION['local_name'] . "\">
            </form>";
        }else if($_SESSION['central_status'] == "Logout" && $_SESSION['local_status'] != "Disconnect")
        {
            echo "<form action=\"05_login_local.php\" style=\"float: left;\">
            <input type=\"submit\" value=\"Conectar Local\">
            </form>";
        }
    ?>
    <form action="04_login.php">
        <input type="submit" value="<?php echo $_SESSION['central_status']; ?>">
    </form>

<form action="02_consultas.php" method="post">
    <table table_align = "left" cellspacing = "5" cellpadding = "8">
        <tr>
            <td align="left"><b>Clientes</b></td>
            <td align="left"><b>Moldes</b></td>
            <td align="left"><b>Sensores</b></td>
            <td align="left"><b>Registos</b></td>
        </tr>
            <tr>
                <td align="left"><input type="checkbox" name="cl_ID" value="cl_ID"> cl_ID</td>
                <td align="left"><input type="checkbox" name="m_ID" value="m_ID"> m_ID</td>
                <td align="left"><input type="checkbox" name="s_num" value="s_num"> s_num</td>
                <td align="left"><input type="checkbox" name="r_data_hora" value="r_data_hora"> r_data_hora</td>
            </tr>
            <tr>
                <td align="left"><input type="checkbox" name="cl_nome" value="cl_nome"> cl_nome</td>
                <td align="left"><input type="checkbox" name="m_nome" value="m_nome"> m_nome</td>
                <td align="left"><input type="checkbox" name="tipo_nome" value="tipo_nome"> tipo_nome</td>
                <td align="left"><input type="checkbox" name="r_milisegundos" value="r_milisegundos"> r_milisegundos</td>
            </tr>
            <tr>
                <td align="left"><input type="checkbox" name="cl_morada" value="cl_morada"> cl_morada</td>
                <td align="left"><input type="checkbox" name="m_descricao" value="m_descricao"> m_descricao</td>
                <td align="left"><input type="checkbox" name="s_nome" value="s_nome"> s_nome</td>
                <td align="left"><input type="checkbox" name="r_valor" value="r_valor"> r_valor</td>
            </tr>
            <tr>
                <td align="left"><input type="checkbox" name="cl_IP" value="cl_IP"> cl_IP</td>
                <td align="left"></td>
                <td align="left"><input type="checkbox" name="s_descricao" value="s_descricao"> s_descricao</td>
                <td align="left"></td>
            </tr>
            <tr>
                <td align="left"><input type="checkbox" name="cl_port" value="cl_port"> cl_port</td>
                <td align="left"></td>
                <td align="left"></td>
                <td align="left"></td>
            </tr>
    </table>
    <table table_align = "left" cellspacing = "5" cellpadding = "8">
        <tr>
            <td>Filtros:</td>
            <td>
                    <input type="text" name="Filtros" value="" size="50">
            </td>
        </tr>
        <tr>
            <td>Ordenar:</td>
            <td>
                    <input type="text" name="Ordem" value="" size="50">
            </td>
        </tr>
        <tr>
            <td valign="top">Query:</td>
            <td>
                <textarea name="Query_Text" rows="5" cols="71" ></textarea>
            </td>
            <td valign="bottom">
                <input type="submit" name="Query" value="Query">
            </td>
        </tr>
    </table>
</form>

<?php
    if(isset($_POST['Query']))
    {
        $atributos = array();

        if(!empty($_POST['cl_ID']))
        {
            $atributos['cl_ID'] = trim($_POST['cl_ID']);
            $queryOption = 1;
        }
        if(!empty($_POST['cl_nome']))
        {
            $atributos['cl_nome'] = trim($_POST['cl_nome']);
            $queryOption = 1;
        }
        if(!empty($_POST['cl_morada']))
        {
            $atributos['cl_morada'] = trim($_POST['cl_morada']);
            $queryOption = 1;
        }
        if(!empty($_POST['cl_IP']))
        {
            $atributos['cl_IP'] = trim($_POST['cl_IP']);
            $queryOption = 1;
        }
        if(!empty($_POST['cl_port']))
        {
            $atributos['cl_port'] = trim($_POST['cl_port']);
            $queryOption = 1;
        }
        if(!empty($_POST['m_ID']))
        {
            $atributos['m_ID'] = trim($_POST['m_ID']);
            $queryOption = 2;
        }
        if(!empty($_POST['m_nome']))
        {
            $atributos['m_nome'] = trim($_POST['m_nome']);
            $queryOption = 2;
        }
        if(!empty($_POST['m_descricao']))
        {
            $atributos['m_descricao'] = trim($_POST['m_descricao']);
            $queryOption = 2;
        }
        if(!empty($_POST['s_num']))
        {
            $atributos['s_num'] = trim($_POST['s_num']);
            $queryOption = 3;
        }
        if(!empty($_POST['tipo_nome']))
        {
            $atributos['tipo_nome'] = trim($_POST['tipo_nome']);
        }
        if(!empty($_POST['s_nome']))
        {
            $atributos['s_nome'] = trim($_POST['s_nome']);
            $queryOption = 3;
        }
        if(!empty($_POST['s_descricao']))
        {
            $atributos['s_descricao'] = trim($_POST['s_descricao']);
            $queryOption = 3;
        }
        if(!empty($_POST['r_data_hora']))
        {
            $atributos['r_data_hora'] = trim($_POST['r_data_hora']);
        }
        if(!empty($_POST['r_milisegundos']))
        {
            $atributos['r_milisegundos'] = trim($_POST['r_milisegundos']);
            $queryOption = 4;
        }
        if(!empty($_POST['r_valor']))
        {
            $atributos['r_valor'] = trim($_POST['r_valor']);
            $queryOption = 4;
        }

        if(!empty($_POST['tipo_nome']) && $queryOption == 0)
        {
            $atributos['tipo_nome'] = "DISTINCT(" . trim($_POST['tipo_nome']) . ")";
            $queryOption = 4;
        }else if(!empty($_POST['r_data_hora']) && $queryOption == 0)
        {
            $atributos['r_data_hora'] = "DISTINCT(" . trim($_POST['r_data_hora']) . ")";
            $queryOption = 4;
        }

        if(!empty($_POST['Filtros']))
        {
            $selecao = trim($_POST['Filtros']);
        }
        if(!empty($_POST['Ordem']))
        {
            $ordem = trim($_POST['Ordem']);
        }

        if(!empty($_POST['Query_Text']))
        {
            $query_text = trim($_POST['Query_Text']);

            $test_select = explode(" ", $query_text);

            if($test_select[0] == "SELECT")
            {
                //https://stackoverflow.com/questions/5127322/output-text-in-between-two-words?utm_medium=organic&utm_source=google_rich_qa&utm_campaign=google_rich_qa
                $word1 = 'SELECT';
                $word2 = 'FROM';
                preg_match('/'.preg_quote($word1).'(.*?)'.preg_quote($word2).'/is', $query_text, $match);

                $atributos = explode(",", $match[1]);
                $numeroDeAtributos = count($atributos);

                $queryOption = 5;
            }else
            {
                echo "Só são aceites queries do tipo SELECT<br>Query anterior: " . $query_text;
            }
        }

        if(!empty($atributos))
        {
            $projecao = implode(", ", $atributos);

            $numeroDeAtributos = count($atributos);

            require('query_consulta.php');
        }else
        {
            echo "Inserir Query";
        }
    }
?>

</body>

</html>