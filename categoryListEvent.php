<?php
require 'connection.php';
ini_set("session.cookie_httponly", 1);
session_start();
header("Content-Type: application/json");

$json_str = file_get_contents('php://input');
//This will store the data into an associative array
$json_obj = json_decode($json_str, true);

//Variables can be accessed as such:
$user_name = $_SESSION['user_name'];
$date = $json_obj['date'];
$eventCategory = $json_obj['category'];
$uid = $_SESSION["uid"];

if ($_SESSION["loggedin"] === true && $_SESSION['user_name'] !== "Guest") {
    
    $events = [];
    $stmt = $mysqli->prepare("SELECT event, event_id, time from events where user_id=? AND date=? AND category=?");
			if (!$stmt) {
				printf("Query Prep Failed: %s\n", $mysqli->error);
				exit;
			}
			$stmt->bind_param('iss', $uid, $date, $eventCategory);
			$stmt->execute();
            $stmt->bind_result($event, $event_id, $time);

            while ($stmt->fetch()) {
                array_push($events, array(
                    "event_id" => $event_id,
                    "event" => $event,
                    "time" => $time
                ));
            }
			$stmt->close();


    echo json_encode(array(
        "success" => true,
        "events" => $events
    ));
    exit;
} else {
    echo json_encode(array(
        "success" => false,
        "message" => "Not Logged In!"
    ));
    exit;
}
