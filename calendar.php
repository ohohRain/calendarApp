<?php
ini_set("session.cookie_httponly", 1);
session_start();
//make sure user logged in!!!!
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
  $_SESSION["loggedin"] = true;
  $_SESSION["user_name"] = "Guest";
}
require 'connection.php';
?>

<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <title>Calendar App</title>

  <link rel="stylesheet" href="calendar.css">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">




</head>

<body>
  <a href="logout.php" class="btn btn-outline-danger signOut">Sign Out Here</a>
  <?php
  if ($_SESSION['user_name'] != "Guest") {
  ?>
    <a href="reset_pwd.php" class="btn btn-outline-primary reset">Reset Password</a>
  <?php }
  ?>

  <div class="hello">
    <?php
    $user = $_SESSION['user_name'];
    if ($_SESSION['user_name'] != "Guest") {
    $id = $_SESSION["uid"];
    }
    else{
      $id = "";
    }
    printf(
      "<p>Hello, %s#%s!</p> \n",
      htmlentities($user),
      htmlentities($id)
    );
    ?>
  </div>

  <div class="calendar">
    <div class="title">
      <h1 class="green" id="calendar-year">Year</h1>
      <h1 class="green" id="calendar-title">Month</h1>
      <a href="" id="prev">Prev Month</a>
      <a href="" id="next">Next Month</a>
    </div>
    <div class="body">
      <div class="lightgrey body-list">
        <ul>
          <li>MON</li>
          <li>TUE</li>
          <li>WED</li>
          <li>THU</li>
          <li>FRI</li>
          <li>SAT</li>
          <li>SUN</li>
        </ul>
      </div>
      <div class="darkgrey body-list">
        <ul id="days">

        </ul>
      </div>


    </div>

    <?php
  if ($_SESSION['user_name'] != "Guest") {
  ?>

    <span class="container"></span>

    <div class="pt-5">
      
      <form id="addEvent" method="POST">
        <label for="fevent">Event:</label>
        <input type="text" id="fevent" name="fevent" required><br>
        <label for="ftime">Time:</label>
        <input type="time" id="ftime" name="ftime" required><br>
        <label for="eventCategory">Event Category:</label>
        <select name="eventCategory" id="eventCategory" required>
            <option value="">Select</option>
            <option value="None">None</option>
            <option value="Study">Study</option>
            <option value="Family">Family</option>
            <option value="Work">Work</option>
            <option value="Social">Social</option>
        </select>
        <input type="hidden" name="token" id="ftoken" value="<?php echo $_SESSION['token']; ?>" />
        <input type="submit" value="Submit" class="btn btn-primary submitButton">
        
        
      </form>
      <form id="addGroupEvent" method="POST">
      <input type="submit" value="Submit As Group" class="btn btn-primary submitButton">
      </form>
      
    </div>

    <?php }
  ?>
    

    <span class="container"></span>

    <div id="container categoryButtons">
        <form>
          <input type="radio" name="button" value="All" id = "All" checked = "checked"> All
          <input type="radio" name="button" value="Study" id = "Study"> Study
          <input type="radio" name="button" value="Work" id = "Work"> Work
          <input type="radio" name="button" value="Family" id = "Family"> Family
          <input type="radio" name="button" value="Social" id = "Social"> Social
        </form>
      </div>

    <div class="container pt-5" id="editDiv" style="display: none">
      <form id="editEvent" method="POST">
        <label for="fevent">Edit Event:</label>
        <input type="text" id="efevent" name="fevent"><br>
        <label for="ftime">Edit Time:</label>
        <input type="time" id="eftime" name="ftime">
        <label for="EditeventCategory">Event Category</label>
        <select name="EditeventCategory" id="EditeventCategory" required>
            <option value="">Select</option>
            <option value="None">None</option>
            <option value="Study">Study</option>
            <option value="Family">Family</option>
            <option value="Work">Work</option>
            <option value="Social">Social</option>
        </select>
        <input type="hidden" name="token" id="eftoken" value="<?php echo $_SESSION['token']; ?>" />
        <input type="submit" value="Submit" class="btn btn-primary submitButton">
      </form>
    </div>

    <div class="container">
      <table id="js-table" class="ml-auto mr-auto mt-5">

      </table>
    </div>


  </div>



</body>


</html>

<script src="calendar.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>