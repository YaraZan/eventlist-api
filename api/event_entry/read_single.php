<?php
    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    include_once '../../config/Database.php';
    include_once '../../models/EventEntry.php';

    // Instantiate DB & connect
    $database = new Database();
    $db = $database->connect();

    // Instantinate event object
    $event_entry = new EventEntry($db);

    // Get ID
    $event_entry->public_id = isset($_GET['public_id']) ? $_GET['public_id'] : die();

    // Get user
    $event_entry->read_single();

    // Create array
    $entry_item = array(
        'public_id' => $event_entry->public_id,
        'event' => $event_entry->event,
        'place' => $event_entry->place,
        'datetime' => $event_entry->datetime,
        'max_people' => $event_entry->max_people,
        'created_at' => $event_entry->created_at
    );

    // Make json
    print_r(json_encode($entry_item));

?>