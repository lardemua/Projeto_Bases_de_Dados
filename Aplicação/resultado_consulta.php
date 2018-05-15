<?php
session_start();
?>
<html lang="pt">
<head>
    <meta charset="UTF-8" />
    <title>Resultado</title>
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

<?php
    if(isset($_GET['Query']))
    {
        $atributos = array();

        if(!empty($_GET['cl_ID']))
        {
            $atributos['cl_ID'] = trim($_GET['cl_ID']);
            $queryOption = 1;
        }
        if(!empty($_GET['cl_nome']))
        {
            $atributos['cl_nome'] = trim($_GET['cl_nome']);
            $queryOption = 1;
        }
        if(!empty($_GET['cl_morada']))
        {
            $atributos['cl_morada'] = trim($_GET['cl_morada']);
            $queryOption = 1;
        }
        if(!empty($_GET['cl_IP']))
        {
            $atributos['cl_IP'] = trim($_GET['cl_IP']);
            $queryOption = 1;
        }
        if(!empty($_GET['cl_port']))
        {
            $atributos['cl_port'] = trim($_GET['cl_port']);
            $queryOption = 1;
        }
        if(!empty($_GET['m_ID']))
        {
            $atributos['m_ID'] = trim($_GET['m_ID']);
            $queryOption = 2;
        }
        if(!empty($_GET['m_nome']))
        {
            $atributos['m_nome'] = trim($_GET['m_nome']);
            $queryOption = 2;
        }
        if(!empty($_GET['m_descricao']))
        {
            $atributos['m_descricao'] = trim($_GET['m_descricao']);
            $queryOption = 2;
        }
        if(!empty($_GET['s_num']))
        {
            $atributos['s_num'] = trim($_GET['s_num']);
            $queryOption = 3;
        }
        if(!empty($_GET['tipo_nome']))
        {
            $atributos['tipo_nome'] = trim($_GET['tipo_nome']);
        }
        if(!empty($_GET['s_nome']))
        {
            $atributos['s_nome'] = trim($_GET['s_nome']);
            $queryOption = 3;
        }
        if(!empty($_GET['s_descricao']))
        {
            $atributos['s_descricao'] = trim($_GET['s_descricao']);
            $queryOption = 3;
        }
        if(!empty($_GET['fase_nome']))
        {
            $atributos['fase_nome'] = trim($_GET['fase_nome']);
        }
        if(!empty($_GET['r_data_hora']))
        {
            $atributos['r_data_hora'] = trim($_GET['r_data_hora']);
        }
        if(!empty($_GET['r_milissegundos']))
        {
            $atributos['r_milissegundos'] = trim($_GET['r_milissegundos']);
            $queryOption = 4;
        }
        if(!empty($_GET['r_valor']))
        {
            $atributos['r_valor'] = trim($_GET['r_valor']);
            $queryOption = 4;
        }

        if(!empty($_GET['tipo_nome']) && $queryOption == 0)
        {
            $atributos['tipo_nome'] = "DISTINCT(" . trim($_GET['tipo_nome']) . ")";
            $queryOption = 4;
        }else if(!empty($_GET['r_data_hora']) && $queryOption == 0)
        {
            $atributos['r_data_hora'] = "DISTINCT(" . trim($_GET['r_data_hora']) . ")";
            $queryOption = 4;
        }else if(!empty($_GET['fase_nome']) && $queryOption == 0)
        {
            $atributos['fase_nome'] = "DISTINCT(" . trim($_GET['fase_nome']) . ")";
            $queryOption = 4;
        }

        if(!empty($_GET['Filtros']))
        {
            $selecao = trim($_GET['Filtros']);
        }
        if(!empty($_GET['Ordem']))
        {
            $ordem = trim($_GET['Ordem']);
        }

        if(!empty($_GET['Query_Text']))
        {
            $query_text = trim($_GET['Query_Text']);

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