<?php
    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Credentials: true');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: POST');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        header('Access-Control-Allow-Origin: http://localhost:8080');
        header('Access-Control-Allow-Methods: POST');
        header('Access-Control-Allow-Headers: *');
        exit();
    }

    include_once '../../config/Database.php';
    include_once '../../models/User.php';

    // Instantiate DB & connect
    $database = new Database();
    $db = $database->connect();

    // Instantinate user object
    $user = new User($db);

    // Get raw posted data
    $data = json_decode(file_get_contents("php://input"));

    // Set values
    $user->name = $data->name;
    $user->email = $data->email;
    $user->password = $data->password;

    // Creating user
    if (
        !empty($user->name) &&
        !empty($user->email) &&
        $user->email_exists() == 0 &&
        !empty($user->password) &&
        $user->create()
    ) {
        //Set responce code
        http_response_code(200);

        // Show message
        echo json_encode(array("message" => "Пользователь был создан"));
    }
    else {
        //Set responce code
        http_response_code(400);

        // Show message
        echo json_encode(array("message" => "Невозможно создать пользователя"));
    }
?>