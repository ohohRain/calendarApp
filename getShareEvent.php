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
$uid = $_SESSION["uid"];
$event_id = $json_obj['event_id'];


if ($_SESSION["loggedin"] === true && $_SESSION['user_name'] !== "Guest") {
    
    $events = [];
    $stmt = $mysqli->prepare("SELECT event,  time , category from events where user_id=? AND date=? AND event_id=?");
			if (!$stmt) {
				printf("Query Prep Failed: %s\n", $mysqli->error);
				exit;
			}
			$stmt->bind_param('isi', $uid, $date, $event_id);
			$stmt->execute();
            $stmt->bind_result($event, $time, $categoey);

            while ($stmt->fetch()) {
                array_push($events, array(
                    "event" => $event,
                    "time" => $time,
                    "category" => $categoey
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
