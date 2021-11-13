<?php
$servername = "localhost";
$username = "wustl_inst";
$password = "wustl_pass";
$database = "calendar";
// Create connection
$mysqli = mysqli_connect($servername, $username, $password, $database);

// Check connection
if (!$mysqli) {
  die("Connection failed: " . mysqli_connect_error());
}

if ($mysqli->connect_errno) {
    printf("Connection Failed: %s\n", $mysqli->connect_error);
    exit;

}


?>