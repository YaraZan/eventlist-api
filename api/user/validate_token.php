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

    // Connecting JWT files
    include_once "../../config/Core.php";
    include_once "../../libs/php-jwt/src/BeforeValidException.php";
    include_once "../../libs/php-jwt/src/ExpiredException.php";
    include_once "../../libs/php-jwt/src/SignatureInvalidException.php";
    include_once "../../libs/php-jwt/src/JWT.php";
    include_once "../../libs/php-jwt/src/Key.php";
    use \Firebase\JWT\JWT;
    use \Firebase\JWT\Key;

    // Get value of JWT
    $data = json_decode(file_get_contents("php://input"));

    // Get a JWT 
    $jwt = isset($data->token) ? $data->token : "";

    // if jwt is not null
    if ($jwt) {

        try {

            // if decoding is success, show user data
            $decoded = JWT::decode($jwt, new Key($key, 'HS256'));

            // Responce code
            http_response_code(200);

            // Show details
            echo json_encode(array(
                "message" => "Доступ разрешён",
                "data" => $decoded->data
            ));
        }

        // If decoding wasnt successfull, JWT is no more valid
        catch (Exception $e) {

            // Responce code
            http_response_code(401);

            // Tell user that he is not available to acess the resource
            echo json_encode(array(
                "message" => "Доступ запрещён",
                "error" => $e->getMessage()
            ));
        } 
    }

    // Show message if JWT is null
    else {
        // Responce code
        http_response_code(401);

        // Tell user that he is not available to acess the resource
        echo json_encode(array(
            "message" => "Доступ запрещён"
        ));
    }

?>