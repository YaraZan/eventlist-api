<?php
    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    include_once '../../config/Database.php';
    include_once '../../models/Event.php';

    require '../../vendor/autoload.php';
    use Ramsey\Uuid\Uuid;

    // Instantiate DB & connect
    $database = new Database();
    $db = $database->connect();

    // Instantinate event object
    $event = new Event($db);

    // Get ID
    $event->public_id = isset($_GET['public_id']) ? $_GET['public_id'] : die();

    // Get event
    $event->read_single();

    // Create array
    $event_item = array(
        'public_id' => $event->public_id,
        'name' => $event->name,
        'descr' => $event->descr,
        'creator' => $event->creator,
        'organisation' => $event->organisation,
        'kind' => $event->kind,
        'type' => $event->type,
        'level' => $event->level,
        'sign_deadline' => $event->sign_deadline,
        'place' => $event->place,
        'isArchieved' => $event->isArchieved,
        'isPrivate' => $event->isPrivate,
        'isPassed' => $event->isPassed,
        'access_code' => $event->access_code,
        'max_people' => $event->max_people,
        'date_start' => $event->date_start,
        'date_end' => $event->date_end,
        'created_at' => $event->created_at
    );

    // Make json
    print_r(json_encode($event_item));

?>