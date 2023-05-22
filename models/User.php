<?php
    class User {
        // DB stuff
        private $conn;
        private $table = 'users';

        // Properties
        public $id;
        public $name;
        public $login;
        public $password;
        public $role;

        // Constructor with DB
        public function __construct($db) {
            $this->conn = $db;
        }

        // Get Creator name by id

        public function read_single() {
            $query = 'SELECT
            r.name as role_name,
            u.id,
            u.name,
            u.login,
            u.password,
            u.role
            FROM 
                ' . $this->table . ' u
            LEFT JOIN usr_roles r ON u.role = r.id
            WHERE 
                u.id = ?
                LIMIT 0,1';
            
            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Bind ID
            $stmt->bindParam(1, $this->id);
            
            // Execute query 
            $stmt->execute();

            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            $this->id = $row['id'];
            $this->name = $row['name'];
            $this->login = $row['login'];
            $this->password = $row['password'];
            $this->role = $row['role'];        
        }
    }
?>