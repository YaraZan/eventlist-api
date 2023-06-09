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
    include_once '../../models/User.php';

    // Instantiate DB & connect
    $database = new Database();
    $db = $database->connect();

    // Instantinate user object
    $user = new User($db);

    // Get raw posted data
    $data = json_decode(file_get_contents("php://input"));

    // Set values
    $user->email = $data->email;
    $email_exists = $user->email_exists();

    // Connecting JWT files
    include_once "../../config/Core.php";
    include_once "../../libs/php-jwt/src/BeforeValidException.php";
    include_once "../../libs/php-jwt/src/ExpiredException.php";
    include_once "../../libs/php-jwt/src/SignatureInvalidException.php";
    include_once "../../libs/php-jwt/src/JWT.php";
    use \Firebase\JWT\JWT;

    // Check if email exists and passwod matches
    if ($email_exists && password_verify($data->password, $user->password)) {

        $token = array(
            "iss" => $iss,
            "aud" => $aud,
            "iat" => $iat,
            "nbf" => $nbf,
            "data" => array(
                "public_id" => $user->public_id,
                "name" => $user->name,
                "email" => $user->email,
                "role" => $user->role
            )
        );

        // Responce code
        http_response_code(200);

        // Creating a JWT
        $jwt = JWT::encode($token, $key, 'HS256');
        echo json_encode(
            array(
                "message" => "Успешный вход в систему",
                "token" => $jwt
            )
        );
    }

    // If email doesnt exist or password doesnt match, tell user he cant login
    else {
        
        // Responce code
        http_response_code(401);

        echo json_encode(
            array("message" => "Ошибка входа",)
        );
    }