<?php
    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    include_once '../../config/Database.php';
    include_once '../../models/Organisation.php';

    // Instantiate DB & connect
    $database = new Database();
    $db = $database->connect();

    // Instantinate event object
    $organisation = new Organisation($db);

    // Get ID
    $organisation->public_id = isset($_GET['public_id']) ? $_GET['public_id'] : die();

    // Get user
    $organisation->read_single();

    // Create array
    $organisation_item = array(
        'public_id' => $organisation->public_id,
        'creator' => $organisation->creator,
        'name' => $organisation->name,
        'email' => $organisation->email,
        'descr' => $organisation->descr,
        'target' => $organisation->target,
        'level' => $organisation->level,
        'type' => $organisation->type,
        'location' => $organisation->location,
        'max_people' => $organisation->max_people
    );

    // Make json
    print_r(json_encode($organisation_item));

?>