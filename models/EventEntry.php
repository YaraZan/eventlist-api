<?php
    class EventEntry {
        // DB stuff
        private $conn;
        private $table = 'event_entries';

        // Properties
        public $id;
        public $public_id;
        public $event;
        public $max_people;
        public $place;
        public $datetime;
        public $created_at;

        // Constructor with DB
        public function __construct($db) {
            $this->conn = $db;
        }

        // Get Posts
        public function read() {
            // Create query
            $query = 'SELECT
                        ev.name as event_name,
                        e.public_id,
                        e.event,
                        e.max_people,
                        e.place,
                        e.datetime,
                        e.created_at
                    FROM 
                        ' . $this->table . ' e
                    LEFT JOIN events ev ON e.event = ev.public_id
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
            ev.name as event_name,
            e.public_id,
            e.event,
            e.max_people,
            e.place,
            e.datetime,
            e.created_at
        FROM 
            ' . $this->table . ' e
            LEFT JOIN events ev ON e.event = ev.public_id
            WHERE 
                e.public_id = ?
                LIMIT 0,1';
            
            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Bind ID
            $stmt->bindParam(1, $this->public_id);
            
            // Execute query 
            $stmt->execute();

            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            $this->public_id = $row['public_id'];
            $this->event = $row['event'];
            $this->max_people = $row['max_people'];
            $this->place = $row['place'];
            $this->datetime = $row['datetime'];
            $this->created_at = $row['created_at'];
        }

        // Create Event
        public function create() {
            // Create query
            $query = 'INSERT INTO ' . $this->table . '
                SET 
                    public_id = :public_id,
                    event = :event,
                    max_people = :max_people,
                    place = :place,
                    datetime = :datetime
            ';

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Clean data
            $this->public_id = htmlspecialchars(strip_tags($this->public_id));
            $this->event = htmlspecialchars(strip_tags($this->event));
            $this->max_people = htmlspecialchars(strip_tags($this->max_people));
            $this->place = htmlspecialchars(strip_tags($this->place));
            $this->datetime = htmlspecialchars(strip_tags($this->datetime));

            // Bind data
            $stmt->bindParam(':public_id', $this->public_id);
            $stmt->bindParam(':event', $this->event);
            $stmt->bindParam(':max_people', $this->max_people);
            $stmt->bindParam(':place', $this->place);
            $stmt->bindParam(':datetime', $this->datetime);

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
                    event = :event,
                    max_people = :max_people,
                    place = :place,
                    datetime = :datetime,
                    created_at = :created_at
                WHERE
                    public_id = :public_id';

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Clean data
            $this->public_id = htmlspecialchars(strip_tags($this->public_id));
            $this->event = htmlspecialchars(strip_tags($this->event));
            $this->max_people = htmlspecialchars(strip_tags($this->max_people));
            $this->place = htmlspecialchars(strip_tags($this->place));
            $this->datetime = htmlspecialchars(strip_tags($this->datetime));
            $this->created_at = htmlspecialchars(strip_tags($this->created_at));

            // Bind data
            $stmt->bindParam(':public_id', $this->public_id);
            $stmt->bindParam(':event', $this->event);
            $stmt->bindParam(':max_people', $this->max_people);
            $stmt->bindParam(':place', $this->place);
            $stmt->bindParam(':datetime', $this->datetime);
            $stmt->bindParam(':created_at', $this->created_at);

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
            $query = 'DELETE FROM ' . $this-> table . ' WHERE public_id = :public_id';

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Clean data
            $this->public_id = htmlspecialchars(strip_tags($this->public_id));

            // Bind data
            $stmt->bindParam(':public_id', $this->public_id);

            // Execute query
            if($stmt->execute()) {
                return true;
            }

            // Print error if something goes wrong
            printf("Error: %s.\n", $stmt->error);

            return false;
        }
        

        // Get Events by organisation
        public function read_by_event() {
            // Create query
            $query = 'SELECT
                ev.name as event_name,
                e.public_id,
                e.event,
                e.max_people,
                e.place,
                e.datetime,
                e.created_at
            FROM 
                ' . $this->table . ' e
            LEFT JOIN events ev ON e.event = ev.public_id
            WHERE
                e.event = ?
            ORDER BY
                e.created_at DESC';

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Bind ID
            $stmt->bindParam(1, $this->event);

            // Execute query 
            $stmt->execute();

            return $stmt;
        }
        
    }
?>