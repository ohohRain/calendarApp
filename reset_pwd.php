<?php

session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login_check.php");
    exit;
}

require 'connection.php';

$new_password = "";
$new_password_err =  "";

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Reset Password</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .warning_message{
			
			margin: auto;
			margin-top: 20px;
            margin-left: 10px;
			font-size: 18px;
			color: red;
		}
        
        .wrapper {
            width: 360px;
            padding: 20px;
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <h2>Reset Password</h2>
        <p>Reset your password here.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label>New Password</label>
                <input type="password" name="new_password" class="form-control <?php echo (!empty($new_password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $new_password; ?>">
                <span class="invalid-feedback"><?php echo $new_password_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Submit">
                <a class="btn btn-link ml-2" href="newsPage.php">Cancel</a>
            </div>
        </form>
    </div>
</body>

</html>
<?php



if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Filter Input
    if (empty(trim($_POST["new_password"]))) {
        $new_password_err = "Password cannot be empty.";
    }
    else if (!preg_match('/^[a-zA-Z0-9_!@#$%^&*()\/]+$/', trim($_POST["new_password"]))) {
        $new_password_err = "Please enter a valid password. Password can only contain letters, numbers, and these characters: !@#$%^&*()/\.";
    } 
    else {
        $new_password = trim($_POST["new_password"]);
    }


    if (empty($new_password_err)) {
        // Prepare an update statement
        $stmt = $mysqli->prepare("UPDATE users SET hashed_password = ? WHERE uid = ?");
        if(!$stmt){
            printf("Query Prep Failed: %s\n", $mysqli->error);
            exit;
        }
        $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);
        $param_uid = $_SESSION["uid"];
        $stmt->bind_param('si', $hashed_password, $param_uid);
        $stmt->execute();
        $stmt->close();
        session_destroy();
        echo "<p class='warning_message'>Reset Password Success! Redirecting to Login...</p>";
        header("refresh:2; url=login_check.php");
        exit();

        
    }
    else {
        echo $new_password_err;
    }

}

?>