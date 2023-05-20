<?php
    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    include_once '../../config/Database.php';
    include_once '../../models/Event.php';

    // Instantiate DB & connect
    $database = new Database();
    $db = $database->connect();

    // Instantinate event object
    $event = new Event($db);

    // Event query
    $result = $event->read();

    // Get row count
    $num = $result->rowCount();

    // Check if any events
    if ($num > 0) {
        $events_arr = array();
        $events_arr['data'] = array();

        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            extract($row);

            $event_item = array(
                'id' => $id,
                'name' => $name,
                'descr' => $descr,
                'creator' => $creator,
                'organisation' => $organisation,
                'kind' => $kind,
                'type' => $type,
                'level' => $level,
                'sign_deadline' => $sign_deadline,
                'place' => $place,
                'isArchieved' => $isArchieved,
                'isPrivate' => $isPrivate,
                'isPassed' => $isPassed,
                'access_code' => $access_code,
                'max_people' => $max_people,
                'date_start' => $date_start,
                'date_end' => $date_end,
                'created_at' => $created_at
            );

            // Push to "Data"
            array_push($events_arr['data'], $event_item);
        }

        // Turn to JSON
        echo json_encode($events_arr);

    } else {
        echo json_encode(
            array('message' => 'No events found')
        );
    }
?>