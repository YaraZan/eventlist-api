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

        // Create Event
        public function create() {
            // Create query
            $query = 'INSERT INTO ' . $this->table . '
                SET 
                    name = :name,
                    descr = :descr,
                    creator = :creator,
                    organisation = :organisation,
                    kind = :kind,
                    type = :type,
                    level = :level,
                    sign_deadline = :sign_deadline,
                    place = :place,
                    isPrivate = :isPrivate,
                    access_code = :access_code,
                    max_people = :max_people,
                    date_start = :date_start,
                    date_end = :date_end
            ';

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Clean data
            $this->name = htmlspecialchars(strip_tags($this->name));
            $this->descr = htmlspecialchars(strip_tags($this->descr));
            $this->creator = htmlspecialchars(strip_tags($this->creator));
            $this->organisation = htmlspecialchars(strip_tags($this->organisation));
            $this->kind = htmlspecialchars(strip_tags($this->kind));
            $this->type = htmlspecialchars(strip_tags($this->type));
            $this->level = htmlspecialchars(strip_tags($this->level));
            $this->sign_deadline = htmlspecialchars(strip_tags($this->sign_deadline));
            $this->place = htmlspecialchars(strip_tags($this->place));
            $this->isPrivate = htmlspecialchars(strip_tags($this->isPrivate));
            $this->access_code = htmlspecialchars(strip_tags($this->access_code));
            $this->max_people = htmlspecialchars(strip_tags($this->max_people));
            $this->date_start = htmlspecialchars(strip_tags($this->date_start));
            $this->date_end = htmlspecialchars(strip_tags($this->date_end));

            // Bind data
            $stmt->bindParam(':name', $this->name);
            $stmt->bindParam(':descr', $this->descr);
            $stmt->bindParam(':creator', $this->creator);
            $stmt->bindParam(':organisation', $this->organisation);
            $stmt->bindParam(':kind', $this->kind);
            $stmt->bindParam(':type', $this->type);
            $stmt->bindParam(':level', $this->level);
            $stmt->bindParam(':sign_deadline', $this->sign_deadline);
            $stmt->bindParam(':place', $this->place);
            $stmt->bindParam(':isPrivate', $this->isPrivate);
            $stmt->bindParam(':access_code', $this->access_code);
            $stmt->bindParam(':max_people', $this->max_people);
            $stmt->bindParam(':date_start', $this->date_start);
            $stmt->bindParam(':date_end', $this->date_end);

            // Execute query
            if($stmt->execute()) {
                return true;
            }

            // Print error if something goes wrong
            printf("Error: %s.\n", $stmt->error);

            return false;
        }

        // Update Event
        public function update() {
            // Create query
            $query = 'UPDATE ' . $this->table . '
                SET 
                    name = :name,
                    descr = :descr,
                    creator = :creator,
                    organisation = :organisation,
                    kind = :kind,
                    type = :type,
                    level = :level,
                    sign_deadline = :sign_deadline,
                    place = :place,
                    isPrivate = :isPrivate,
                    access_code = :access_code,
                    max_people = :max_people,
                    date_start = :date_start,
                    date_end = :date_end
                WHERE
                    id = :id';

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Clean data
            $this->id = htmlspecialchars(strip_tags($this->id));
            $this->name = htmlspecialchars(strip_tags($this->name));
            $this->descr = htmlspecialchars(strip_tags($this->descr));
            $this->creator = htmlspecialchars(strip_tags($this->creator));
            $this->organisation = htmlspecialchars(strip_tags($this->organisation));
            $this->kind = htmlspecialchars(strip_tags($this->kind));
            $this->type = htmlspecialchars(strip_tags($this->type));
            $this->level = htmlspecialchars(strip_tags($this->level));
            $this->sign_deadline = htmlspecialchars(strip_tags($this->sign_deadline));
            $this->place = htmlspecialchars(strip_tags($this->place));
            $this->isPrivate = htmlspecialchars(strip_tags($this->isPrivate));
            $this->access_code = htmlspecialchars(strip_tags($this->access_code));
            $this->max_people = htmlspecialchars(strip_tags($this->max_people));
            $this->date_start = htmlspecialchars(strip_tags($this->date_start));
            $this->date_end = htmlspecialchars(strip_tags($this->date_end));

            // Bind data
            $stmt->bindParam(':id', $this->id);
            $stmt->bindParam(':name', $this->name);
            $stmt->bindParam(':descr', $this->descr);
            $stmt->bindParam(':creator', $this->creator);
            $stmt->bindParam(':organisation', $this->organisation);
            $stmt->bindParam(':kind', $this->kind);
            $stmt->bindParam(':type', $this->type);
            $stmt->bindParam(':level', $this->level);
            $stmt->bindParam(':sign_deadline', $this->sign_deadline);
            $stmt->bindParam(':place', $this->place);
            $stmt->bindParam(':isPrivate', $this->isPrivate);
            $stmt->bindParam(':access_code', $this->access_code);
            $stmt->bindParam(':max_people', $this->max_people);
            $stmt->bindParam(':date_start', $this->date_start);
            $stmt->bindParam(':date_end', $this->date_end);

            // Execute query
            if($stmt->execute()) {
                return true;
            }

            // Print error if something goes wrong
            printf("Error: %s.\n", $stmt->error);

            return false;
        }

        // Delete Event
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