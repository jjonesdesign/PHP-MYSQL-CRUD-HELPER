<?php
class NetworkConnection{
    
    public static $connection = null;
    
    public function __construct() {
        
    }
    
    public static function getNewDbConnection(){
        
        try{
            $dsn = "mysql:host=".DB_HOST.";dbname=".DB_NAME.";charset=" . DB_CHARSET;
            $opt = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];

            self::$connection = new PDO($dsn, DB_USER, DB_PASSWORD, $opt);

            return self::$connection;
        } catch (Exception $ex) {
            throw new \PDOException($ex->getMessage(), (int)$ex->getCode());
        }
        
    }
    
    public static function closeConnection(){
        self::$connection = null;
    }
}

