<?php
    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: POST');
    header("Access-Control-Max-Age: 3600");
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

    // Connecting JWT files
    include_once "../../config/Core.php";
    include_once "../../libs/php-jwt/src/BeforeValidException.php";
    include_once "../../libs/php-jwt/src/ExpiredException.php";
    include_once "../../libs/php-jwt/src/SignatureInvalidException.php";
    include_once "../../libs/php-jwt/src/JWT.php";
    include_once "../../libs/php-jwt/src/Key.php";
    use \Firebase\JWT\JWT;
    use \Firebase\JWT\Key;

    // Necessary files
    include_once '../../config/Database.php';
    include_once '../../models/User.php';

     // Instantiate DB & connect
     $database = new Database();
     $db = $database->connect();
 
     // Instantinate user object
     $user = new User($db);
 
     // Get raw posted data
     $data = json_decode(file_get_contents("php://input"));

     // Get JWt
     $jwt = isset($data->jwt) ? $data->jwt : "";

     // if JWT is not null
     if ($jwt) {

        // if decoding is successfull, show user data
        try {

            // Decoding JWT
            $decoded = JWT::decode($jwt, new Key($key, 'HS256'));

            // We need to set posted values from client to object attributes
            $user->name = $data->name;
            $user->email = $data->email;
            $user->password = $data->password;
            $user->id = $data->id;

            // Creating a user 
            if ($user->update()) {
                $token = array(
                    "iss" => $iss,
                    "aud" => $aud,
                    "iat" => $iat,
                    "nbf" => $nbf,
                    "data" => array(
                        "id" => $user->id,
                        "name" => $user->name,
                        "email" => $user->email
                    )
                );

                $jwt = JWT::encode($token, $key, 'HS256');

                // Response code
                http_response_code(200);

                echo json_encode(array(
                    "message" => "Пользователь был обновлён",
                    "jwt" => $jwt
                ));
            }

            // Message if its impossible to change user credentials
            else {

                // Response code
                http_response_code(401);

                // Show error message
                echo json_encode(array("message" => "Невозможно обновить пользователя"));
            }
        }

        // If decoding wasnt successfull, JWT is not valid
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

     // Show message if jwt is null
     else {

        // Responce code
        http_response_code(401);

        // Tell user that he is not available to acess the resource
        echo json_encode(array("message" => "Доступ запрещён"));
     }

