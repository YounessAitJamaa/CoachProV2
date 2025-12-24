<?php

    class Database {
        private $host = 'localhost';    
        private $db = 'CoachPro';
        private $user = 'root';
        private $password = 'Youcode@2025';

        private ?PDO $conn = null;
        

        public function getConnection() {
            
            if($this->conn === null) {
                try {
                    $this->conn = new PDO(
                        "mysql:host={$this->host};dbname={$this->db}", $this->user, $this->password
                    );

                    $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    
                }catch (PDOException $err) {
                    die('Database connection problem: '. $err->getMessage());   
                }
            }
            return $this->conn;
        }

    }

?>