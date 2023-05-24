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

    // Get ID
    $event_type->id = isset($_GET['id']) ? $_GET['id'] : die();

    // Get event
    $event_type->read_single();

    // Create array
    $event_type_item = array(
        'id' => $event->id,
        'name' => $event->name,
        'created_at' => $event->created_at
    );

    // Make json
    print_r(json_encode($event_type_item));

?>