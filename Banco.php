<?php

class Banco
{
    private static $dbNome = 'webness_smtlog';
    private static $dbHost = '177.53.142.253:3303';
    private static $dbUsuario = 'webness_smtlog';
    private static $dbSenha = 'u)3d%?-5-nk9';

    /*
     private static $dbNome = 'harmonizenet_harmonize';
     private static $dbHost = 'localhost';
     private static $dbUsuario = 'harmonizenet_admin';
     private static $dbSenha = 'harmonizenet_admin';
     */
    private static $cont = null;
    
    public function __construct()
    {
        die('A fun��o Init nao � permitido!');
    }
    
    public static function conectar()
    {
        if(null == self::$cont)
        {
            try
            {
                self::$cont =  new PDO( "mysql:host=".self::$dbHost.";"."dbname=".self::$dbNome, self::$dbUsuario, self::$dbSenha);
            }
            catch(PDOException $exception)
            {
                die($exception->getMessage());
            }
        }
        return self::$cont;
    }
    
    public static function desconectar()
    {
        self::$cont = null;
    }
}