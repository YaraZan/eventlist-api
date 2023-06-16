<?php
    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Credentials: true');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: POST');
    header('Access-Control-Allow-Headers: *');

    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: POST');
        header('Content-Type: application/json');
        header('Access-Control-Allow-Headers: *');
        exit();
    }

    include_once '../../config/Database.php';
    include_once '../../models/EventEntry.php';

    // Instantiate DB & connect
    $database = new Database();
    $db = $database->connect();

    // Instantinate event object
    $event_entry = new EventEntry($db);

    $data = json_decode(file_get_contents("php://input"));

    $event_entry->event = $data->event;

    // Event query
    $result = $event_entry->read_by_event();

    // Get row count
    $num = $result->rowCount();

    // Check if any events
    if ($num > 0) {
        $entries_arr = array();
        $entries_arr['data'] = array();

        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            extract($row);

            $entry_item = array(
                'public_id' => $public_id,
                'event' => $event,
                'place' => $place,
                'datetime' => $datetime,
                'max_people' => $max_people,
                'created_at' => $created_at
            );

            // Push to "Data"
            array_push($entries_arr['data'], $entry_item);
        }

        // Turn to JSON
        echo json_encode($entries_arr);

    } else {
        echo json_encode(
            array('message' => 'No entries found')
        );
    }
?>