<?php
    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: DELETE');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

    include_once '../../config/Database.php';
    include_once '../../models/Event.php';

    // Instantiate DB & connect
    $database = new Database();
    $db = $database->connect();

    // Instantinate event object
    $event = new Event($db);

    // Get raw posted data
    $data = json_decode(file_get_contents("php://input"));

    // Set id to delete 
    $event->id = $data->id;

    // Delete event
    if ($event->delete()) {
        echo json_encode(
            array('message' => 'Event Deleted')
        );
    } else {
        echo json_encode(
            array('message' => 'Event not Deleted')
        );
    }
?>