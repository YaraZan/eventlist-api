<?php
    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: POST');
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

    $event->name = $data->name;
    $event->descr = $data->descr;
    $event->creator = $data->creator;
    $event->organisation = $data->organisation;
    $event->kind = $data->kind;
    $event->type = $data->type;
    $event->level = $data->level;
    $event->sign_deadline = $data->sign_deadline;
    $event->place = $data->place;
    $event->isPrivate = $data->isPrivate;
    $event->access_code = $data->access_code;
    $event->max_people = $data->max_people;
    $event->date_start = $data->date_start;
    $event->date_end = $data->date_end;


    // Create event
    if ($event->create()) {
        echo json_encode(
            array('message' => 'Event Created')
        );
    } else {
        echo json_encode(
            array('message' => 'Event not Created')
        );
    }

?>