<?php
    class Database{
        private $host = 'localhost';
        private $user = 'root';
        private $pass = '';
        private $dbname = 'hotel_events';
        private $conn;
        private static $instance = null;

        private function __construct(){
            $this->connect();
        }

        public static function getInstance(){
            if(self::$instance === null){
                self::$instance = new Database();
            }
            return self::$instance;
        }

        private function connect(){
            $this->conn = new mysqli($this->host, $this->user, $this->pass, $this->dbname);

            // Check connection
            if($this->conn->connect_error){
                // Handle connection error
                die("Connection failed:" . $this->conn->connect_error);
            }
            $this->conn->set_charset('utf8mb4');
        }

        public function getConnection(){
            return $this->conn;
        }

        public function closeConnection(){
            if($this->conn){
                $this->conn->close();
            }
        }

    }

    //sample usage
    $db = Database::getInstance();
    $con = $db->getConnection();

?>