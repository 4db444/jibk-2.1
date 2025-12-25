<?php
    namespace Database;

    use PDO;

    class Database {
        private static ?PDO $connection = null;
        
        private function __construct(){
            try{
                self::$connection = new PDO("mysql:host=localhost;dbname=jibk2.0", "root", "Brahim@444");
            }catch(PDOException $e){
                die("wrong db credentials !");
            }
        }

        private function __clone(){}

        public function __wakeup(){}

        public static function instance (): PDO{
            if(self::$connection === null) new self();
            return self::$connection;
        }
    }