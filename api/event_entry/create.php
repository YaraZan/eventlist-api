<?php
    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: POST');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

    include_once '../../config/Database.php';
    include_once '../../models/EventEntry.php';

    require '../../vendor/autoload.php';
    use Ramsey\Uuid\Uuid;

    // Instantiate DB & connect
    $database = new Database();
    $db = $database->connect();

    // Instantinate event object
    $event_entry = new EventEntry($db);

    // Get raw posted data
    $data = json_decode(file_get_contents("php://input"));

    $uuid = Uuid::uuid4();

    $event_entry->public_id = $uuid->toString();

    $event_entry->event = $data->event;
    $event_entry->max_people = $data->max_people;
    $event_entry->place = $data->place;
    $event_entry->datetime = $data->datetime;


    // Create event
    if ($event_entry->create()) {
        echo json_encode(
            array('message' => 'Entry Created')
        );
    } else {
        echo json_encode(
            array('message' => 'Entry not Created')
        );
    }

?>