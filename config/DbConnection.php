<?php

    class DbConnection {

        private static $instance = NULL;
        private $host       = '127.0.0.1';
        private $user       = 'root';
        private $pass       = 'root';
        private $db         = 'corephp';
        private $errorFile  = 'db_errors.log';
        private $conn;

        private function __construct()
        {
            try{
                $this->conn = new PDO("mysql:host=".$this->host.";dbname=".$this->db,$this->user,$this->pass);
                $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            }catch(PDOException $exception){
                $this->errorLog($exception);
                die("Connection Error : Please Check Error Log File.");
            }
        }

        public function errorLog($exception) {
            $errorMsg = "[".date('Y-m-d H:i:s')."] ".$exception->getMessage()."\n";
            file_put_contents($this->errorFile, $errorMsg, FILE_APPEND);
        }

        public static function getInstance(){
            if(self::$instance == NULL){
                self::$instance = new DbConnection();
            }
            return self::$instance;
        }

        public function getConnection(){
            return $this->conn;
        }

        private function __clone()
        {
            
        }

        private function __wakeup(){

        }
    }
?>