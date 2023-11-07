<?php
    require_once 'Conexao.php';

    // Cabeçalhos
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
    header("Access-Control-Allow-Methods: POST, GET, PUT, DELETE, OPTIONS");  
    header('Content-type: application/json');
    //header('Content-Type: application/x-www-form-urlencoded');

    $tab = $_POST["tabela"];
    $data = json_decode($_POST["data"], true);

    // Console PHP
    $arquivo = '../../../consolePHP.json';
    file_put_contents($arquivo, $data["info"]['numero']);

    if($tab == 'comunicados') {
        $sql_Update = 'UPDATE comunic_usuarios_info SET visto = 1 WHERE id = ' . $data;
        Conexao::executar($sql_Update);
    }

    if($tab == 'perfil') {
        if($data["tipo"] == 'telefone') {
            $tel = str_replace("(", "", "{$data["info"]}");
            $tel = str_replace(")", "", $tel);
            $tel = str_replace("-", "", $tel);
            $tel = str_replace(" ", "", $tel);
            $sql_Update = 'UPDATE users SET telefone = ' . $tel . ' WHERE id = ' . $data["id"];
            Conexao::executar($sql_Update);
        } else {
            $sql_Update = 'UPDATE users SET senha = "' . $data["info"] . '" WHERE id = ' . $data["id"];
            Conexao::executar($sql_Update);
        }
    }

    //include_once 'db.php';
    echo $tab;
?>