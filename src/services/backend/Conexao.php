<?php
    class Conexao {
        const HOST = 'localhost',
            DRIVER = 'mysql',
            BANCO = 'vuejs_5',
            USER = 'dougMoura',
            SENHA = '22_Moura_@',
            OPCOES = [
                PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
                PDO::ATTR_CASE => pdo::CASE_NATURAL,
            ];

        static function executar(string $query, bool $emObjeto = false){
            $pdo = new PDO(
                static::DRIVER . ':host=' . static::HOST . ';dbname=' .
                static::BANCO,
                static::USER,
                static::SENHA,
                static::OPCOES,
            );

            $sql = $pdo->prepare($query);
            $sql->execute();

            if($emObjeto)
                return $sql->fetchAll(\PDO::FETCH_CLASS);
            else
                return $sql->fetchAll(\PDO::FETCH_ASSOC);
        }
    }
?>