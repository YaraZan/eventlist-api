<?php
    class Event {
        // DB stuff
        private $conn;
        private $table = 'events';

        // Properties
        public $id;
        public $name;
        public $descr;
        public $creator;
        public $organisation;
        public $kind;
        public $type;
        public $level;
        public $sign_deadline;
        public $place;
        public $isArchieved;
        public $isPrivate;
        public $isPassed;
        public $access_code;
        public $max_people;
        public $date_start;
        public $date_end;
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
                        l.name as level_name,
                        u.name as creator_name,
                        o.name as organisation_name,
                        e.id,
                        e.name,
                        e.descr,
                        e.creator;
                        e.organisation;
                        e.type,
                        e.kind,
                        e.level,
                        e.sign_deadline,
                        e.place,
                        e.isArchieved,
                        e.isPrivate,
                        e.isPassed,
                        e.access_code,
                        e.max_people,
                        e.date_start,
                        e.date_end,
                        e.created_at
                    FROM 
                        ' . $this->table . ' p
                    LEFT JOIN
                        ev_types t ON e.type = t.id,
                        ev_kinds k ON e.kind = k.id,
                        ev_levels l ON e.level = l.id,
                        users u ON e.organisator = u.id,
                        organisations o ON e.organisation = o.id,
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