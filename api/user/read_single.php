<?php
    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    include_once '../../config/Database.php';
    include_once '../../models/User.php';

    // Instantiate DB & connect
    $database = new Database();
    $db = $database->connect();

    // Instantinate event object
    $user = new User($db);

    // Get ID
    $user->public_id = isset($_GET['public_id']) ? $_GET['public_id'] : die();

    // Get user
    $user->read_single();

    // Create array
    $user_item = array(
        'public_id' => $user->public_id,
        'name' => $user->name,
        'email' => $user->email,
        'role' => $user->role
    );

    // Make json
    print_r(json_encode($user_item));

?>