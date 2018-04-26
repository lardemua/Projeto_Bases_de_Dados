<html lang="pt">
<head>
    <meta charset="UTF-8" />
    <title>Administração</title>
</head>
<body>

<?php
echo "Hello";
    if(isset($_POST['Adicionar_Cliente']))
    {

            $cl_port = trim($_POST['cl_port']);
            echo $cl_port;
            echo "World";
    }
?>
<form action="/test.php" method="post">
        <legend>Cliente:</legend>
                <td>Port:</td>
                <td>
                    <input type="text" name="cl_port" value="">
                </td>
            </tr>
        </table>
        <input type="submit" name="Adicionar_Cliente" value="Adicionar Cliente">
    </form>

</body>
</html>