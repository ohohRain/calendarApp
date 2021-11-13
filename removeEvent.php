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
$event_id = $json_obj['event_id'];
$uid = $_SESSION["uid"];

if ($_SESSION["loggedin"] === true && $_SESSION['user_name'] !== "Guest") {
    

    $stmt = $mysqli->prepare("DELETE from events where event_id=? AND user_id=?");
			if (!$stmt) {
				printf("Query Prep Failed: %s\n", $mysqli->error);
				exit;
			}
			$stmt->bind_param('ii', $event_id, $uid);
			$stmt->execute();
			$stmt->close();


    echo json_encode(array(
        "success" => true
    ));
    exit;
} else {
    echo json_encode(array(
        "success" => false,
        "message" => "Not Logged In!"
    ));
    exit;
}
