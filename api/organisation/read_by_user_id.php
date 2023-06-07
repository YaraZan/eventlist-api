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

    // Instantinate event object
    $organisation = new Organisation($db);

    $data = json_decode(file_get_contents("php://input"));

    $organisation->creator = $data->creator;

    // Event query
    $result = $organisation->read_by_user_id();

    // Get row count
    $num = $result->rowCount();

    // Check if any events
    if ($num > 0) {
        $orgs_arr = array();
        $orgs_arr['data'] = array();

        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            extract($row);

            $org_item = array(
                'id' => $id,
                'creator' => $creator,
                'name' => $name,
                'email' => $email,
                'descr' => $descr,
                'target' => $target,
                'level' => $level,
                'type' => $type,
                'location' => $location,
                'max_people' => $max_people,
            );

            // Push to "Data"
            array_push($orgs_arr['data'], $org_item);
        }

        // Turn to JSON
        echo json_encode($orgs_arr);

    } else {
        echo json_encode(
            array('message' => 'No organisations found')
        );
    }
?>