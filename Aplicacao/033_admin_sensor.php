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

        if(isset($_POST['Adicionar_Sensor']))
        {
           $data_missing = array();
           $data_wrong = array();
           $go = 0;
    
           if(empty($_POST['s_molde']))
           {
               $data_missing[] = 'ID Molde';
           }else{
               $s_molde = trim($_POST['s_molde']);
           }
           if(!is_numeric($s_molde))
           {
               $data_wrong[] = 'ID Molde';
           }
    
           if(empty($_POST['s_sensor']))
           {
               $data_missing[] = 'Número do Sensor';
           }else{
               $s_sensor = trim($_POST['s_sensor']);
           }
           if(!is_numeric($s_sensor))
           {
               $data_wrong[] = 'Número do Sensor';
           }
    
           if(empty($_POST['s_tipo']))
           {
               $data_missing[] = 'Tipo de Sensor';
           }else{
               $s_tipo = trim($_POST['s_tipo']);
           }
           if(!is_numeric($s_tipo))
           {
               $data_wrong[] = 'Tipo de Sensor';
           }
    
           if(empty($_POST['s_nome']))
           {
               $s_nome = "NULL";
           }else{
               $s_nome = trim($_POST['s_nome']);
           }
    
           if(empty($_POST['s_descricao']))
           {
               $s_descricao = "NULL";
           }else{
               $s_descricao = trim($_POST['s_descricao']);
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
               require('temp_local_connect.php');
    
               $query = "INSERT INTO sensores VALUES (?,?,?,?,?)";
    
               $stmt = mysqli_prepare($dbc4,$query);
    
               mysqli_stmt_bind_param($stmt, "iiiss", $s_molde, $s_sensor, $s_tipo, $s_nome, $s_descricao);
    
               mysqli_stmt_execute($stmt);
    
               $affected_rows = mysqli_stmt_affected_rows($stmt);
    
               if($affected_rows == 1)
               {
                   //echo "Sensor criado com sucesso";
               }else
               {
                   echo "Erro: " . mysqli_error($dbc4);
               }
               mysqli_stmt_close($stmt);
               mysqli_close($dbc4);
           }
       }
    
       if(isset($_POST['Eliminar_Sensor']))
       {
        $data_missing = array();
        $data_wrong = array();
        $go = 0;
    
            if(empty($_POST['s_molde']))
            {
                $data_missing[] = 'ID Molde';
            }else{
                $s_molde = trim($_POST['s_molde']);
            }
            if(!is_numeric($s_molde))
            {
                $data_wrong[] = 'ID Molde';
            }
    
            if(empty($_POST['s_sensor']))
            {
                $data_missing[] = 'Número do Sensor';
            }else{
                $s_sensor = trim($_POST['s_sensor']);
            }
            if(!is_numeric($s_sensor))
            {
                $data_wrong[] = 'Número do Sensor';
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
            require('temp_local_connect.php');
            $query = "DELETE FROM sensores WHERE s_IDMolde = " . $m_molde . "AND S_num = " . $s_sensor;

            if (!mysqli_query($dbc4,$query))
            {
                echo("Erro: " . mysqli_error($dbc4) . "<br>");
            }else
            {
            //echo "sensor eliminado";
            }
            mysqli_close($dbc4);
               
          }
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
<!-- 
    <form action="034_admin_eliminar.php">
        <input type="submit" value="Eliminar">
    </form>
 -->
    <p><b>Temporária:</b></p>
    <?php
        require('query_sensor_temp.php');
    ?>

    <form action="033_admin_sensor.php" method = "post">
        <input type="submit" name="Validar" value="Validar">
        <br>
        <br>
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
                <td> Tipo:</td>
                <td>
                    <select name="s_tipo">
                        <option value="1">Termómetro</option>
                        <option value="2">Dinamómetro</option>
                        <option value="3">Extensómetro</option>
                        <option value="4">Vibrómetro</option>
                        <option value="5">Pressão</option>
                        <option value="6">Acelerómetro X</option>
                        <option value="7">Acelerómetro Y</option>
                        <option value="8">Acelerómetro Z</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td> Nome:</td>
                <td>
                    <input type="text" name="s_nome" value="">
                </td>
            </tr>
            <tr>
                <td valign="top"> Descrição: </td>
                <td>
                    <textarea name="s_descricao" rows="5" cols="33" maxlength="200"></textarea>
                </td>
            </tr>
        </table>
        <input type="submit" name="Adicionar_Sensor" value="Adicionar Sensor" style="float: left;">
        <input type="submit" name="Eliminar_Sensor" value="Eliminar Sensor">
        <br>
        <br>
        <input type="submit" name="Ver_Cliente" value="Ver Clientes">
        <input type="submit" name="Ver_Molde" value="Ver Moldes">
        <input type="submit" name="Ver_Sensor" value="Ver Sensores">
    </form>

<?php

   if(!isset($_POST['Ver_Cliente']) && !isset($_POST['Ver_Molde']) && !isset($_POST['Ver_Sensor']))
   {
       require('query_sensor.php');
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

   if(isset($_POST['Validar']))
   {
           require('local_connect.php');
           require('central_connect.php');
           require('temp_local_connect.php');
           require('query_validar.php');
           mysqli_close($dbc2);
           mysqli_close($dbc);
           mysqli_close($dbc4);

           ob_start(); // ensures anything dumped out will be caught
           $url = '03_administracao.php'; // this can be set based on whatever

           // clear out the output buffer
           while (ob_get_status()) 
           {
               ob_end_clean();
           }

           // no redirect
           header( "Location: $url" );
   }

?>

</body>
</html>
