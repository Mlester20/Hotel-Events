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
                // Throw exception instead of die
                throw new Exception("Database connection failed: " . $this->conn->connect_error);
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

    // Initialize database connection
    try {
        $db = Database::getInstance();
        $con = $db->getConnection();
    } catch (Exception $e) {
        // Log but don't die - let the calling code handle errors
        error_log("Database initialization error: " . $e->getMessage());
        // Create a flag that we can check
        $con = null;
    }

?>