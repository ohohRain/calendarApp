<?php

ini_set("session.cookie_httponly", 1);
session_start();
?>


<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<title>login</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
	<style>
		body {
			background: #eee !important;
		}

		.wrapper {
			margin-top: 80px;
			margin-bottom: 80px;
		}

		.form-signin {
			max-width: 380px;
			padding: 15px 35px 45px;
			margin: 0 auto;
			background-color: #fff;
			border: 1px solid rgba(0, 0, 0, 0.1);
		}

		.form-signin-heading {
			margin: auto;
			margin-bottom: 30px;

		}

		.container {
			max-width: 380px;
		}

		.form-control {
			position: relative;
			font-size: 16px;
			height: auto;
			padding: 10px;
		}

		input[type="text"] {
			margin-bottom: -1px;
			border-bottom-left-radius: 0;
			border-bottom-right-radius: 0;
		}

		.button {
			margin-top: 20px;
			display: block;
			width: 100%;
			padding-right: 0;
			padding-left: 0;
			padding: 10px 16px;
			font-size: 18px;
			line-height: 1.33;
			border-radius: 6px;
			color: #fff;
			background-color: #428bca;
			border-color: #357ebd;

		}

		.warning_message {

			margin: auto;
			margin-top: 20px;
			font-size: 18px;
			color: red;
		}
	</style>
</head>

<body>
	<div class="wrapper">
		<form class="form-signin" method="post">
			<h2 class="form-signin-heading">Please login</h2>
			<input type="text" name="userName" placeholder="User Name" class="form-control">
			<input type="password" name="userPassword" placeholder="Password" class="form-control">
			<input type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>">
			<br>


			<button name="login" value="login" class="button">
				Login
			</button>
			<button name="guestLogin" value="guestLogin" class="button">Guest Login </button>
		</form>


	</div>

	<div class="container">
		<button class="button" onclick="location.href='signup.php'">Sign up</button>

		<?php
		$wrong_user_name = 1;
		require 'connection.php';

		if (isset($_POST['guestLogin'])) {
			$user_name = 'Guest';
			$user_password = 'guest';

			$_SESSION["loggedin"] = true;
			$_SESSION["user_name"] = $user_name;

			header("location: calendar.php");
		}


		if (isset($_POST['login'])) { //if user clicked the login button, do following:

			$user_name = $_POST['userName'];
			$user_password = $_POST['userPassword'];

			if ($user_name == "") {
				echo "<p class='warning_message'>Please enter a valid user name</p>";
				exit;
			} else if (!preg_match('/^[a-zA-Z0-9_]+$/', trim($_POST["userName"]))) {
				echo "<p class='warning_message'>Username can only contain letters, numbers, and underscores.</p>";
				exit;
			} else if ($user_password == "") {
				echo "<p class='warning_message'>Please enter a valid password</p>";
				exit;
			} else if (!preg_match('/^[a-zA-Z0-9_!@#$%^&*()\/]+$/', trim($_POST["userPassword"]))) {
				echo "<p class='warning_message'>Please enter a valid password.</p>";
				exit;
			} else {
				$stmt = $mysqli->prepare("SELECT uid, user_name, hashed_password FROM users WHERE user_name = ?");
				if (!$stmt) {
					printf("Query Prep Failed: %s\n", $mysqli->error);
					exit;
				}
				$stmt->bind_param('s', $user_name);
				if (mysqli_stmt_execute($stmt)) {
					/* store result */
					mysqli_stmt_store_result($stmt);

					if (mysqli_stmt_num_rows($stmt) == 1) {
						mysqli_stmt_bind_result($stmt, $uid, $user_name, $hashed_password);
						if (mysqli_stmt_fetch($stmt)) {
							if (password_verify($user_password, $hashed_password)) {

								// Store data in session variables
								$_SESSION["loggedin"] = true;
								$_SESSION["uid"] = $uid;
								$_SESSION["user_name"] = $user_name;

								// Redirect user to welcome page

								//header("location: welcome.php");
								$_SESSION['token'] = bin2hex(openssl_random_pseudo_bytes(32));
								header("location: calendar.php");
							} else {
								echo "<p class='warning_message'>Invalid username or password</p>";
							}
						}
					} else {
						echo "<p class='warning_message'>Invalid username or password</p>";
					}
				} else {
					echo "Something went wrong. Please try again later.";
				}
				$stmt->close();
			}
		}





		?>
	</div>
</body>

</html>