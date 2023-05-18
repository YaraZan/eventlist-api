<?php
    class Event {
        // DB stuff
        private $conn;
        private $table = 'events';

        // Properties
        public $id;
        public $name;
        public $descr;
        public $type_id;
        public $kind_id;
        public $created_at;

        // Constructor with DB
        public function __construct($db) {
            $this->conn = $db;
        }

        // Get Posts
        public function read() {
            // Create query
            $query = 'SELECT
                        t.name as type_name,
                        k.name as kind_name,
                        e.id,
                        e.name,
                        e.descr,
                        e.type_id,
                        e.kind_id,
                        e.created_at
                    FROM 
                        ' . $this->table . ' p
                    LEFT JOIN
                        types t ON e.type_id = t.id,
                        kinds k ON e.kind_id = k.id
                    ORDER BY
                        e.created_at DESC'

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Execute query 
            $stmt->execute();

            return $stmt;
        }
    }
?>