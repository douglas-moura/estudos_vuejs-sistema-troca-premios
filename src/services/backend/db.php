<?php
    require_once 'Conexao.php';

    header("Access-Control-Allow-Origin: *");

    // Seleção de Dados
    $query = 'SELECT * FROM users';
    $queryPremios = 'SELECT * FROM premios WHERE ativo = 1';

    // Conexão com o Banco de Dados
    $result = Conexao::executar($query);
    $resultPremios = Conexao::executar($queryPremios);

    // VARIAVEIS AUXILIARES
    $meses = "`mes_1`, `mes_2`, `mes_3`, `mes_4`, `mes_5`, `mes_6`, `mes_7`, `mes_8`, `mes_9`, `mes_10`, `mes_11`, `mes_12`";
    $mesesArray = [
        "mes_1", "mes_2", "mes_3", "mes_4", "mes_5", "mes_6", "mes_7", "mes_8", "mes_9", "mes_10", "mes_11", "mes_12"
    ];

    // ARRAYS VAZIOS PARA RECEBER DADOS
    $dados = array();
    $categorias = array();
    $pedidos = array();
    
    // BANCO DE DADOS CATALOGO
    for($i = 0; $i < count($resultPremios); $i++) {
        $categorias[$i] = $resultPremios[$i]['categoria'];
    }

    $dados['catalogo']['categorias'] = array_unique($categorias);
    $dados['catalogo']['produtos'] = $resultPremios;

    // BAANCO DE DADOS USUARIOS
    for($i = 0; $i < count($result); $i++) {
        // buscar ENDERECO no banco de dados
        $queryEndereco = 'SELECT * FROM users_enderecos WHERE user_id = ' . $result[$i]['id'];
        $endereco = Conexao::executar($queryEndereco);
        $result[$i]['endereco'] = $endereco;

        // buscar PONTOS no banco de dados
        $queryPontos = 'SELECT ' . $meses . ' FROM users_pontos_base WHERE user_id = ' . $result[$i]['id'];
        $pontosUser = Conexao::executar($queryPontos);
        $result[$i]['pontuacao']['pontos_base'] = $pontosUser;

        $queryPontos_B = 'SELECT ' . $meses . ' FROM users_pontos_bonus WHERE user_id = ' . $result[$i]['id'];
        $bonusUser = Conexao::executar($queryPontos_B);
        $result[$i]['pontuacao']['pontos_bonus'] = $bonusUser;

        $queryPontos_E = 'SELECT ' . $meses . ' FROM users_pontos_extorno WHERE user_id = ' . $result[$i]['id'];
        $extornosUser = Conexao::executar($queryPontos_E);
        $result[$i]['pontuacao']['pontos_extornados'] = $extornosUser;

        $queryPontos_O = 'SELECT ' . $meses . ' FROM users_pontos_obs WHERE user_id = ' . $result[$i]['id'];
        $obsPontos = Conexao::executar($queryPontos_O);
        $result[$i]['pontuacao']['pontos_observacoes'] = $obsPontos;
        
        // buscar METAS no banco de dados
        $queryMetas = 'SELECT ' . $meses . ', `meta_anual` FROM users_metas WHERE user_id = ' . $result[$i]['id'];
        $metasUser = Conexao::executar($queryMetas);
        $result[$i]['desempenho']['metas'] = $metasUser;
        
        // buscar VENDAS no banco de dados
        $vendasUser = null;
        $queryVendas = 'SELECT ' . $meses . ', `realizado_anual` FROM users_vendas WHERE user_id = ' . $result[$i]['id'];
        $vendasUser = Conexao::executar($queryVendas);
        $result[$i]['desempenho']['vendas'] = $vendasUser;

        /* buscar PEDIDOS no banco de dados
        $queryPedidos = 'SELECT * FROM pedidos WHERE user_id = ' . $result[$i]['id'];
        $pedidosUser = Conexao::executar($queryPedidos);
        $result[$i]['pedidos'] = $pedidosUser;

        // buscar ITENS no banco de dados
        $queryPedidosItens = 'SELECT * FROM pedidos_itens';
        $pedidosItens = Conexao::executar($queryPedidosItens);

        for($p = 0; $p < count($pedidosUser); $p++) {
            for($j = 0; $j < count($pedidosItens); $j++) {
                if($pedidosUser[$p]['codigo'] == $pedidosItens[$j]['pedido_codigo']) {
                    $result[$i]['pedidos'][$p]['itens'][$j] = $pedidosItens[$j];
                }
            }
        }*/

        // criar array de DESEMPENHOS
        for($v = 0; $v < 12; $v++) {
            if($vendasUser != null) {
                if($vendasUser[0][$mesesArray[$v]] != null) {
                    $result[$i]['desempenho']['realizado'][0]['mes_' . $v + 1] = number_format((float)($vendasUser[0][$mesesArray[$v]] / $metasUser[0][$mesesArray[$v]]) * 100, 2, '.', '');
                } else {
                    $result[$i]['desempenho']['realizado'][0]['mes_' . $v + 1] = null;
                }

            }
        }

        if(!isset($result[$i]['desempenho']['realizado'])) {
            $result[$i]['desempenho']['realizado'] = [];
        }
        
        // buscar COMUNCADOS no banco de dados
        $queryComunic = 'SELECT comunic_usuarios_info.*, comunic_modelos.mensagem FROM comunic_usuarios_info LEFT JOIN comunic_modelos ON comunic_usuarios_info.modelo = comunic_modelos.nome WHERE (user_id = ' . $result[$i]['id'] . ' || user_id = 0 )';
        $comunicadosUser = Conexao::executar($queryComunic);
        $result[$i]['desempenho']['comunicados'][0]['qtd_comunicados'] = count($comunicadosUser);
        for($c = 0; $c < 12; $c++) {
            $mes = 'mes_' . $c + 1;
            for($f = 0; $f < count($comunicadosUser); $f++) {
                if($comunicadosUser[$f]['periodo'] == $mes) {
                    $result[$i]['desempenho']['comunicados'][0][$mes][] = $comunicadosUser[$f];
                }
            }
        }

        // criar array do usuario
        $dados['usuarios'][$result[$i]['id']] = $result[$i];
    }

    // buscar PEDIDOS no banco de dados
    $qryPedidos = 'SELECT * FROM pedidos';
    $pedidosGeral = Conexao::executar($qryPedidos);
    $dados['pedidos'] = $pedidosGeral;

    // buscar ITENS no banco de dados
    $qryItens = 'SELECT * FROM pedidos_itens';
    $itens = Conexao::executar($qryItens);

    for($a = 0; $a < count($pedidosGeral); $a++) {
        for($b = 0; $b < count($itens); $b++) {
            if($pedidosGeral[$a]['codigo'] == $itens[$b]['pedido_codigo']) {
                $status = 'Solicitado';
                if($itens[$b]['cancelado'] == 0) {
                    if($itens[$b]['status_entrega'] != null) {
                        $status = 'Entregue';
                    } else if($itens[$b]['status_em_rota'] != null) {
                        $status = 'Em rota de entrega';
                    } else if($itens[$b]['status_processado'] != null) {
                        $status = 'Processado';
                    }
                } else {
                    $status = 'Cancelado';
                }
                $itens[$b]['status_atual'] = $status;

                $dados['pedidos'][$a]['itens'][] = $itens[$b];
            }
        }
    }

    // Criando JSON de usuarios
    $arquivo = '../../../db.json';
    $body = json_encode($dados, JSON_PRETTY_PRINT);
    file_put_contents($arquivo, $body);

    // VIASUALIZAR BANCO DE DADOS
    //echo '<pre>';
    echo $body;
    //echo '</pre>';
?>