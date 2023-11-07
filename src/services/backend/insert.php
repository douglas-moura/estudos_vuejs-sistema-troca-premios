<?php
    require_once 'Conexao.php';

    // Cabeçalhos
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
    header("Access-Control-Allow-Methods: POST, GET, PUT, DELETE, OPTIONS");  
    header('Content-type: application/json');
    //header('Content-Type: application/x-www-form-urlencoded');

    $tab = $_POST['tabela'];
    $filename = 'http://localhost:3000/' . $tab;
    $dataJSON = json_decode(file_get_contents($filename), true);
    $data = json_decode($_POST['data'], true);
    
    // Console PHP
    $arquivo = '../../../consolePHP.json';
    file_put_contents($arquivo, $data["info"]);
     
    if($tab == 'usuarios') {
        $nome = "{$data['nome']}";
        $sobrenome = "{$data['sobrenome']}";
        $email = "{$data['email']}";
        $telefone = "{$data['telefone']}";
        $senha = "{$data['senha']}";
        
        $sql = "INSERT INTO users (
            nome,
            sobrenome,
            email,
            telefone,
            senha)
                VALUES (
                    '$nome',
                    '$sobrenome',
                    '$email',
                    '$telefone',
                    '$senha')";
        Conexao::executar($sql);
    }

    if($tab == 'pedidos') {
        $codigo = "{$data["codigo"]}";
        $user_id = "{$data["user_id"]}";
        $qtd_itens = "{$data["qtd_itens"]}";
        $valor = "{$data["valor"]}";
        $frete = "{$data["frete"]}";
        $endereco = "{$data["entrega"]["id"]}";
        $itens_pedido = $data['itens'];        
        
        $sql = "INSERT INTO pedidos (
            codigo,
            user_id,
            qtd_itens,
            valor,
            frete,
            end_entrega,
            data_pedido)
                VALUES (
                    '" . $codigo . "',
                    " . $user_id . ",
                    " . $qtd_itens . ",
                    " . $valor . ",
                    " . $frete . ",
                    " . $endereco . ",
                    NOW()
                )";
                                        
        Conexao::executar($sql);

        for($i = 0; $i < count($itens_pedido); $i++) {
            $sqlItem = "INSERT INTO pedidos_itens (
                pedido_codigo,
                produto_codigo,
                nome_prod,
                valor,
                valor_total,
                quantidade)
                    VALUES (
                        '" . $data["codigo"] . "',
                        " . $itens_pedido[$i]["cod_prod"] . ",
                        '" . $itens_pedido[$i]["nome_prod"] . "',
                        " . $itens_pedido[$i]["valor_prod"] . ",
                        " . $itens_pedido[$i]["valor_total"] . ",
                        " . $itens_pedido[$i]["qtd_prod"] . ")";
            Conexao::executar($sqlItem);
        }
    }
    
    if($tab == 'endereco') {
        $user_id = $data["id"];
        $endereco = "{$data["info"]['endereco']}";
        $n = "{$data["info"]['numero']}";
        $complemento = "{$data["info"]['compl']}";
        $bairro = "{$data["info"]['bairro']}";
        $cep = "{$data["info"]['cep']}";
        $cidade = "{$data["info"]['cidade']}";
        $uf = "{$data["info"]['uf']}";
        
        $sql = "INSERT INTO users_enderecos (
            user_id,
            endereco,
            numero,
            compl,
            bairro,
            cep,
            cidade,
            uf)
                VALUES (
                    '$user_id',
                    '$endereco',
                    '$n',
                    '$complemento',
                    '$bairro',
                    '$cep',
                    '$cidade',
                    '$uf')";
        Conexao::executar($sql);
    }
?>