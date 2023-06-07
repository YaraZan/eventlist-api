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
    include_once '../../models/Organisation.php';

    // Instantiate DB & connect
    $database = new Database();
    $db = $database->connect();

    // Instantinate user object
    $organisation = new Organisation($db);

    // Get raw posted data
    $data = json_decode(file_get_contents("php://input"));

    // Set values
    $organisation->creator = $data->creator;
    $organisation->name = $data->name;
    $organisation->email = $data->email;
    $organisation->descr = $data->descr;
    $organisation->target = $data->target;
    $organisation->level = $data->level;
    $organisation->type = $data->type;
    $organisation->location = $data->location;
    $organisation->max_people = $data->max_people;

    // Create event
    if ($organisation->create()) {
        echo json_encode(
            array('message' => 'Organisation Created')
        );
    } else {
        echo json_encode(
            array('message' => 'Organisation not Created')
        );
    }
?>