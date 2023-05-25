<?php
    class User {
        // DB stuff
        private $conn;
        private $table = 'users';

        // Properties
        public $id;
        public $name;
        public $email;
        public $password;
        public $role_id;
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
            u.email,
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
            $this->email = $row['email'];
            $this->role_id = $row['role'];        
        }

        // Creating user
        public function create() {
            // Query 
            $query = "INSERT INTO " . $this->table . "
            SET
                name = :name,
                email = :email,
                password = :password,
                role = 5";

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Inject 
            $this->name = htmlspecialchars(strip_tags($this->name));
            $this->email = htmlspecialchars(strip_tags($this->email));
            $this->password = htmlspecialchars(strip_tags($this->password));

            // Bind params
            $stmt->bindParam(":name", $this->name);
            $stmt->bindParam(":email", $this->email);

            // Hash password befor inserting in database
            $password_hash = password_hash($this->password, PASSWORD_BCRYPT);
            $stmt->bindParam(":password", $password_hash);

            // Execute
            if ($stmt->execute()) {
                return true;
            }

            return false;
        }

        // Check if email exists
        public function email_exists() {

            // Query to check if email exists
            $query = "SELECT * 
                FROM " . $this->table . "
                    WHERE email = ?
                    LIMIT 0,1";

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Inject 
            $this->email = htmlspecialchars(strip_tags($this->email));

            // Bind params
            $stmt->bindParam(1, $this->email);

            // Execute
            $stmt->execute();

            // Get rows count
            $num = $stmt->rowCount();

            // If exists, set values to object attributes for easy access via php sessions
            if ($num > 0) {

                // Get values
                $row = $stmt->fetch(PDO::FETCH_ASSOC);

                // Set values to object attributes
                $this->id = $row["id"];
                $this->name = $row["name"];
                $this->email = $row["email"];
                $this->password = $row["password"];
                $this->role_id = $row["role"];

                // Set user permissions
                $this->set_permissions();

                // Return true if exists
                return true;
            }

            // Return false if not
            return false;
        }

        // Update user
        public function update() {

            // If in html form was entered password (must change the password)
            $password_set=!empty($this->password) ? ", password = :password" : "";

            // If password is not set - dont change password
            $query = "UPDATE " . $this->table . "
                SET 
                    name = :name,
                    email = :email
                    {$password_set}
                WHERE
                    id = :id";

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Inject 
            $this->name = htmlspecialchars(strip_tags($this->name));
            $this->email = htmlspecialchars(strip_tags($this->email));
            $this->id = htmlspecialchars(strip_tags($this->id));

            // Bind params
            $stmt->bindParam(":name", $this->name);
            $stmt->bindParam(":email", $this->email);

            // Method password_hash() for defending user in database
            if (!empty($this->password)) {
                $this->password=htmlspecialchars(strip_tags($this->password));
                $password_hash = password_hash($this->password, PASSWORD_BCRYPT);
                $stmt->bindParam(":password", $password_hash);
            }

            // Unique id for user
            $stmt->bindParam(":id", $this->id);

            // 
            if($stmt->execute()) {
                return true;
            }

            return false;
        }

        public function set_permissions() {
            // Create query 
            $query = "SELECT * FROM usr_roles 
                WHERE 
                    id = ?
                LIMIT 0,1";

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Bind ID
            $stmt->bindParam(1, $this->role_id);
            
            // Execute query 
            $stmt->execute();

            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            $this->role = array(
                "id" => $row['id'],
                "name" => $row['name'],
                "permissions" => $row["permissions"]
            );

        }
    }
?>