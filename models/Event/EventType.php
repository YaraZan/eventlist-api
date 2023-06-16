<?php
    class EventType {
        // DB stuff
        private $conn;
        private $table = 'ev_types';

        // Properties
        public $id;
        public $name;
        public $created_at;

        // Constructor with DB
        public function __construct($db) {
            $this->conn = $db;
        }

        // Get Posts
        public function read() {
            // Create query
            $query = 'SELECT
                        t.id,
                        t.name,
                        t.descr,
                        t.created_at
                    FROM 
                        ' . $this->table . ' t
                    ORDER BY
                        t.created_at DESC';

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Execute query 
            $stmt->execute();

            return $stmt;
        }

        // Get single Event type
        public function read_single() {
            $query = 'SELECT
            t.id,
            t.name,
            t.created_at
            FROM 
                ' . $this->table . ' t
            WHERE 
                t.id = ?
                LIMIT 0,1';
            
            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Bind ID
            $stmt->bindParam(1, $this->id);
            
            // Execute query 
            $stmt->execute();

            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            $this->name = $row['name'];
            $this->created_at = $row['created_at'];
        }

        // Create Event type
        public function create() {
            // Create query
            $query = 'INSERT INTO ' . $this->table . '
                SET 
                    name = :name,
            ';

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Clean data
            $this->name = htmlspecialchars(strip_tags($this->name));

            // Bind data
            $stmt->bindParam(':name', $this->name);

            // Execute query
            if($stmt->execute()) {
                return true;
            }

            // Print error if something goes wrong
            printf("Error: %s.\n", $stmt->error);

            return false;
        }

        // Delete Event type
        public function delete() {
            // Create query
            $query = 'DELETE FROM ' . $this-> table . ' WHERE id = :id';

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Clean data
            $this->id = htmlspecialchars(strip_tags($this->id));

            // Bind data
            $stmt->bindParam(':id', $this->id);

            // Execute query
            if($stmt->execute()) {
                return true;
            }

            // Print error if something goes wrong
            printf("Error: %s.\n", $stmt->error);

            return false;
        }
        
    }
?>