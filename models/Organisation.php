<?php
    class Organisation {
        // DB stuff
        private $conn;
        private $table = 'organisations';

        // Properties
        public $id;
        public $creator;
        public $name;
        public $email;
        public $descr;
        public $target;
        public $level;
        public $type;
        public $location;
        public $max_people;

        // Constructor with DB
        public function __construct($db) {
            $this->conn = $db;
        }

        // Get Organisations
        public function read() {
            // Create query
            $query = 'SELECT
                ta.name as target_name,
                l.name as level_name,
                ty.name as type_name,
                u.name as creator_name,
                o.id,
                o.creator,
                o.name,
                o.email,
                o.descr,
                o.target,
                o.level,
                o.type,
                o.location,
                o.max_people
            FROM 
                ' . $this->table . ' o
            LEFT JOIN org_levels l ON o.level = l.id
            LEFT JOIN org_targets ta ON o.target = ta.id
            LEFT JOIN org_types ty ON o.type = ty.id
            LEFT JOIN users u ON o.creator = u.id
            ORDER BY
                o.created_at DESC';

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Execute query 
            $stmt->execute();

            return $stmt;
        }

        // Get single event
        public function read_single() {
            $query = 'SELECT
                ta.name as target_name,
                l.name as level_name,
                ty.name as type_name,
                u.name as creator_name,
                o.id,
                o.creator,
                o.name,
                o.email,
                o.descr,
                o.target,
                o.level,
                o.type,
                o.location,
                o.max_people
            FROM 
                ' . $this->table . ' o
            LEFT JOIN org_levels l ON o.level = l.id
            LEFT JOIN org_targets ta ON o.target = ta.id
            LEFT JOIN org_types ty ON o.type = ty.id
            LEFT JOIN users u ON o.creator = u.id
            ORDER BY
                o.created_at DESC';
            
            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Bind ID
            $stmt->bindParam(1, $this->id);
            
            // Execute query 
            $stmt->execute();

            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            
            $this->id = $row['id'];
            $this->creator = $row['creator'];
            $this->name = $row['name'];
            $this->email = $row['email'];
            $this->descr = $row['descr'];
            $this->target = $row['target'];
            $this->level = $row['level'];
            $this->type = $row['type'];
            $this->location = $row['location'];
            $this->max_people = $row['max_people'];
        }

        // Create Organisation
        public function create() {
            // Create query
            $query = 'INSERT INTO ' . $this->table . '
                SET
                    creator = :creator,
                    name = :name,
                    email = :email,
                    descr = :descr,
                    target = :target,
                    level = :level,
                    type = :type,
                    location = :location,
                    max_people = :max_people
            ';

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Clean data
            $this->creator = htmlspecialchars(strip_tags($this->creator));
            $this->name = htmlspecialchars(strip_tags($this->name));
            $this->email = htmlspecialchars(strip_tags($this->email));
            $this->descr = htmlspecialchars(strip_tags($this->descr));
            $this->target = htmlspecialchars(strip_tags($this->target));
            $this->level = htmlspecialchars(strip_tags($this->level));
            $this->type = htmlspecialchars(strip_tags($this->type));
            $this->location = htmlspecialchars(strip_tags($this->location));
            $this->max_people = htmlspecialchars(strip_tags($this->max_people));

            // Bind data
            $stmt->bindParam(':creator', $this->creator);
            $stmt->bindParam(':name', $this->name);
            $stmt->bindParam(':email', $this->email);
            $stmt->bindParam(':descr', $this->descr);
            $stmt->bindParam(':target', $this->target);
            $stmt->bindParam(':level', $this->level);
            $stmt->bindParam(':type', $this->type);
            $stmt->bindParam(':location', $this->location);
            $stmt->bindParam(':max_people', $this->max_people);

            // Execute query
            if($stmt->execute()) {
                return true;
            }

            // Print error if something goes wrong
            printf("Error: %s.\n", $stmt->error);

            return false;
        }

        // Get Events by organisation
        public function read_by_user_id() {
            // Create query
            $query = 'SELECT
                ta.name as target_name,
                l.name as level_name,
                ty.name as type_name,
                u.name as creator_name,
                o.id,
                o.creator,
                o.name,
                o.email,
                o.descr,
                o.target,
                o.level,
                o.type,
                o.location,
                o.max_people
            FROM 
                ' . $this->table . ' o
            LEFT JOIN org_levels l ON o.level = l.id
            LEFT JOIN org_targets ta ON o.target = ta.id
            LEFT JOIN org_types ty ON o.type = ty.id
            LEFT JOIN users u ON o.creator = u.id
            WHERE
                o.creator = ?
            ORDER BY
                o.created_at DESC';

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Bind ID
            $stmt->bindParam(1, $this->creator);

            // Execute query 
            $stmt->execute();

            return $stmt;
        }
    }
?>