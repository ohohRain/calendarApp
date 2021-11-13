<?php
require 'connection.php';
ini_set("session.cookie_httponly", 1);
session_start();
header("Content-Type: application/json");

$json_str = file_get_contents('php://input');
//This will store the data into an associative array
$json_obj = json_decode($json_str, true);

//Variables can be accessed as such:
$event = $json_obj['event'];
$time = $json_obj['time'];
$token = $json_obj['token'];
$date = $json_obj['date'];
$uid = $_SESSION["uid"];
$event_id = $json_obj['event_id'];


if ($token === $_SESSION['token'] && $_SESSION["loggedin"] === true && $_SESSION['user_name'] !== "Guest") {
    
    
    $stmt = $mysqli->prepare("UPDATE events set event=?, time=?, category=? where user_id=? AND date=? AND event_id=?");
			if (!$stmt) {
				printf("Query Prep Failed: %s\n", $mysqli->error);
				exit;
			}
			$stmt->bind_param('sssisi', $event, $time, $eventCategory, $uid, $date, $event_id);
			$stmt->execute();
			$stmt->close();


    echo json_encode(array(
        "success" => true
    ));
    exit;
} else {
    echo json_encode(array(
        "success" => false,
        "message" => "Failed"
    ));
    exit;
}
?>