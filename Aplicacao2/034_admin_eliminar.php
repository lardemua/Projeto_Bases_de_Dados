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
    <form action="031_admin_cliente.php" style="float: left;">
        <input type="submit" value="Clientes">
    </form>
    <form action="032_admin_molde.php" style="float: left;">
        <input type="submit" value="Moldes">
    </form>
    <form action="033_admin_sensor.php" style="float: left;">
        <input type="submit" value="Sensores">
    </form>
    <form action="034_admin_eliminar.php">
        <input type="submit" value="Eliminar">
    </form>

<?php
    require('query_molde.php');
?>

    <form action="034_admin_eliminar.php" method = "post">
        <legend>Eliminar Molde:</legend>
        <table>
            <tr>
                <td>ID Molde:</td>
                <td>
                    <input type="text" name="m_IDMolde" value="">
                </td>
            </tr>
        </table>
        <input type="submit" name="Eliminar_Molde" value="Eliminar Molde">
    </form>

<?php
    if(isset($_POST['Eliminar_Molde']))
    {
        if(empty($_POST['m_IDMolde']))
        {
            $data_missing[] = 'ID Molde';
        }else{
            $m_IDMolde = trim($_POST['m_IDMolde']);
        }
        if(!is_numeric($m_IDMolde))
        {
            $data_wrong[] = 'ID Molde';
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
           //require('eliminar_molde.php');
            echo "molde eliminado";
       }
   }
?>

</body>
</html>