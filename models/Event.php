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
                        e.creator,
                        e.organisation,
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
                        ' . $this->table . ' e
                    LEFT JOIN ev_types t ON e.type = t.id
                    LEFT JOIN ev_kinds k ON e.kind = k.id
                    LEFT JOIN ev_levels l ON e.level = l.id
                    LEFT JOIN users u ON e.creator = u.id
                    LEFT JOIN organisations o ON e.organisation = o.id
                    ORDER BY
                        e.created_at DESC';

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Execute query 
            $stmt->execute();

            return $stmt;
        }

        // Get single event
        public function read_single() {
            $query = 'SELECT
            t.name as type_name,
            k.name as kind_name,
            l.name as level_name,
            u.name as creator_name,
            o.name as organisation_name,
            e.id,
            e.name,
            e.descr,
            e.creator,
            e.organisation,
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
                ' . $this->table . ' e
            LEFT JOIN ev_types t ON e.type = t.id
            LEFT JOIN ev_kinds k ON e.kind = k.id
            LEFT JOIN ev_levels l ON e.level = l.id
            LEFT JOIN users u ON e.creator = u.id
            LEFT JOIN organisations o ON e.organisation = o.id
            WHERE 
                e.id = ?
                LIMIT 0,1';
            
            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Bind ID
            $stmt->bindParam(1, $this->id);
            
            // Execute query 
            $stmt->execute();

            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            $this->name = $row['name'];
            $this->descr = $row['descr'];
            $this->creator = $row['creator'];
            $this->organisation = $row['organisation'];
            $this->type = $row['type'];
            $this->kind = $row['kind'];
            $this->level = $row['level'];
            $this->sign_deadline = $row['sign_deadline'];
            $this->place = $row['place'];
            $this->isArchieved = $row['isArchieved'];
            $this->isPrivate = $row['isPrivate'];
            $this->isPassed = $row['isPassed'];
            $this->access_code = $row['access_code'];
            $this->max_people = $row['max_people'];
            $this->date_start = $row['date_start'];
            $this->date_end = $row['date_end'];
            $this->created_at = $row['created_at'];
        }
    }
?>