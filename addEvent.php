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
$eventCategory = $json_obj['eventCategory'];
$_SESSION["category"] = $eventCategory;

if ($token === $_SESSION['token'] && $_SESSION["loggedin"] === true && $_SESSION['user_name'] !== "Guest") {
    
    

    $stmt = $mysqli->prepare("INSERT INTO events (user_id, event, date, time, category) VALUES (?, ?, ?, ?, ?)");
			if (!$stmt) {
				printf("Query Prep Failed: %s\n", $mysqli->error);
				exit;
			}
			$stmt->bind_param('issss', $uid, $event, $date, $time, $eventCategory);
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