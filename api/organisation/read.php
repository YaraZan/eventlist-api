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
    $org = new Organisation($db);

    // Event query
    $result = $org->read();

    // Get row count
    $num = $result->rowCount();

    // Check if any events
    if ($num > 0) {
        $orgs_arr = array();
        $orgs_arr['data'] = array();

        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            extract($row);

            $org_item = array(
                'public_id' => $public_id,
                'creator' => $creator,
                'name' => $name,
                'email' => $email,
                'descr' => $descr,
                'target' => $target,
                'level' => $level,
                'type' => $type,
                'location' => $location,
                'max_people' => $max_people
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