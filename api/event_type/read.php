<?php
    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    include_once '../../config/Database.php';
    include_once '../../models/EventType.php';

    // Instantiate DB & connect
    $database = new Database();
    $db = $database->connect();

    // Instantinate event object
    $event_type = new EventType($db);

    // Event query
    $result = $event_type->read();

    // Get row count
    $num = $result->rowCount();

    // Check if any events
    if ($num > 0) {
        $event_types_arr = array();
        $event_types_arr['data'] = array();

        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            extract($row);

            $event_type_item = array(
                'id' => $id,
                'name' => $name,
                'created_at' => $created_at
            );

            // Push to "Data"
            array_push($event_types_arr['data'], $event_type_item);
        }

        // Turn to JSON
        echo json_encode($event_types_arr);

    } else {
        echo json_encode(
            array('message' => 'No event types found')
        );
    }
?>