<?php
    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: POST');
    header("Access-Control-Max-Age: 3600");
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

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
    include_once "config/core.php";
    include_once "libs/php-jwt/BeforeValidException.php";
    include_once "libs/php-jwt/ExpiredException.php";
    include_once "libs/php-jwt/SignatureInvalidException.php";
    include_once "libs/php-jwt/JWT.php";
    use \Firebase\JWT\JWT;