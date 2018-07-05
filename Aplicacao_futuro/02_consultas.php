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
        <input type="submit" value="Administração Local">
    </form>
    <form action="04_login.php">
        <input type="submit" value="<?php echo $_SESSION['central_status']; ?>">
    </form>

<form action="resultado_consulta.php" target="_blank" method="get">
	<fieldset>
	<legend>Gerar Query</legend>
	<fieldset style="float: left;">
	<legend>Escolher Atributos a Visualizar:</legend>
    <table style="float: left;" cellspacing = "5" cellpadding = "8">
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
                <td align="left"><input type="checkbox" name="fase_nome" value="fase_nome"> fase_nome</td>
            </tr>
            <tr>
                <td align="left"><input type="checkbox" name="cl_nome" value="cl_nome"> cl_nome</td>
                <td align="left"><input type="checkbox" name="m_nome" value="m_nome"> m_nome</td>
                <td align="left"><input type="checkbox" name="tipo_nome" value="tipo_nome"> tipo_nome</td>
                <td align="left"><input type="checkbox" name="r_data_hora" value="r_data_hora"> r_data_hora</td>
            </tr>
            <tr>
                <td align="left"><input type="checkbox" name="cl_morada" value="cl_morada"> cl_morada</td>
                <td align="left"><input type="checkbox" name="m_descricao" value="m_descricao"> m_descricao</td>
                <td align="left"><input type="checkbox" name="s_nome" value="s_nome"> s_nome</td>
                <td align="left"><input type="checkbox" name="r_milissegundos" value="r_milissegundos"> r_milissegundos</td>
            </tr>
            <tr>
                <td align="left"><input type="checkbox" name="cl_IP" value="cl_IP"> cl_IP</td>
                <td align="left"></td>
                <td align="left"><input type="checkbox" name="s_descricao" value="s_descricao"> s_descricao</td>
                <td align="left"><input type="checkbox" name="r_valor" value="r_valor"> r_valor</td>
            </tr>
            <tr>
                <td align="left"><input type="checkbox" name="cl_port" value="cl_port"> cl_port</td>
                <td align="left"></td>
                <td align="left"></td>
                <td align="left"></td>
            </tr>
    </table>
	</fieldset>
	<fieldset style="float: left;">
	<legend>Adicionar Condições:</legend>
    <table table_align = "left" cellspacing = "5" cellpadding = "8">
        <tr>
            <td>Filtros:</td>
            <td>
                    <abbr title="Ex.: cl_ID = xxxxx AND m_ID = zzzz"><input type="text" name="Filtros" value="" size="43"></abbr>
            </td>
        </tr>
        <tr>
            <td>Ordenar:</td>
            <td>
			<abbr title="Ex.: cl_ID, m_ID"><input type="text" name="Ordem" value="" size="43"></abbr>
            </td>
        </tr>
	</table>
	</fieldset>
	</fieldset>
	<fieldset style="float: left;">
	<legend>Ou:</legend>
    	<table table_align = "left" cellspacing = "5" cellpadding = "8">
        <tr>
            <td valign="top">Query:</td>
            <td>
                <textarea name="Query_Text" rows="5" cols="71" ></textarea>
            </td>
	</tr>
	</table>
	</fieldset>
	<br>
	<br>
	<br>
	<br>
	<br>
	<table table_align = "left" cellspacing = "5" cellpadding = "8">
	<tr>
            <td valign="bottom">
                <input type="submit" name="Query" value="Gerar Query">
            </td>
        </tr>
    </table>
</form>

</body>

</html>
