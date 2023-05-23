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
    $user->id = isset($_GET['id']) ? $_GET['id'] : die();

    // Get user
    $user->read_single();

    // Create array
    $user_item = array(
        'id' => $user->id,
        'name' => $user->name,
        'login' => $user->login,
        'password' => $user->password,
        'role' => $user->role
    );

    // Make json
    print_r(json_encode($user_item));

?>