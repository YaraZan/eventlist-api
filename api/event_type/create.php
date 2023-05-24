<?php
    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: POST');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

    include_once '../../config/Database.php';
    include_once '../../models/EventType.php';

    // Instantiate DB & connect
    $database = new Database();
    $db = $database->connect();

    // Instantinate event object
    $event_type = new EventType($db);

    // Get raw posted data
    $event_type = json_decode(file_get_contents("php://input"));

    $event_type->name = $data->name;

    // Create event type
    if ($event_type->create()) {
        echo json_encode(
            array('message' => 'Event type Created')
        );
    } else {
        echo json_encode(
            array('message' => 'Event type not Created')
        );
    }

?>