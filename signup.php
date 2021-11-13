<!DOCTYPE html>
<html lang="en">

<head>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
	<meta charset="UTF-8">
	<title>Signup</title>
	<style>
		body {
			background: #eee !important;
		}

		.wrapper {
			margin-top: 80px;
			margin-bottom: 80px;
		}

		.form-signin {
			max-width: 580px;
			padding: 15px 35px 45px;
			margin: 0 auto;
			background-color: #fff;
			border: 1px solid rgba(0, 0, 0, 0.1);
		}

		.form-signin-heading {
			margin: auto;
			margin-bottom: 30px;

		}



		.form-control {
			position: relative;
			font-size: 16px;
			height: auto;
			padding: 10px;
			@include box-sizing(border-box);
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
			<h2 class="form-signin-heading">Please Signup new User here</h2>
			<input type="text" name="userName" placeholder="User Name" class="form-control">
			<input type="password" name="userPassword" placeholder="Password" class="form-control">
			<br>


			<button name="signup" value="signup" type="signup" class="button">
				Signup
			</button>
		</form>

	</div>

	<div class="container">
		<button class="button" onclick="location.href='login_check.php'">Return to Login</button>

		<?php
		$wrong_user_name = 0;

		require 'connection.php';
		if (isset($_POST['signup'])) { //if user clicked the login button, do following:

			$user_name = $_POST['userName'];
			$user_password = $_POST['userPassword'];
			$hashed_password = password_hash($user_password, PASSWORD_BCRYPT);

			if ($user_name == "") {
				echo "<p class='warning_message'>Please Input a valid user name</p>";
				exit;
			} else if (!preg_match('/^[a-zA-Z0-9_]+$/', trim($_POST["userName"]))) {
				echo "<p class='warning_message'>Username can only contain letters, numbers, and underscores.</p>";
				exit;
			} else if ($user_password == "") {
				echo "<p class='warning_message'>Please enter a valid password</p>";
				exit;
			} else if (!preg_match('/^[a-zA-Z0-9_!@#$%^&*()\/]+$/', trim($_POST["userPassword"]))) {
				echo "<p class='warning_message'>Please enter a valid password. Password can only contain letters, numbers, and these characters: !@#$%^&*()/\</p>";
				exit;
			} else {
				$stmt = $mysqli->prepare("SELECT uid FROM users WHERE user_name = ?");
				if (!$stmt) {
					printf("Query Prep Failed: %s\n", $mysqli->error);
					exit;
				}
				$stmt->bind_param('s', $user_name);
				if (mysqli_stmt_execute($stmt)) {
					/* store result */
					mysqli_stmt_store_result($stmt);

					if (mysqli_stmt_num_rows($stmt) == 1) {
						echo "<p class='warning_message'>User name is already in use!</p>";
						exit;
					}
				} else {
					echo "Something went wrong. Please try again later.";
				}
				$stmt->close();
			}

			$stmt = $mysqli->prepare("INSERT INTO users (user_name, hashed_password) VALUES (?, ?)");
			if (!$stmt) {
				printf("Query Prep Failed: %s\n", $mysqli->error);
				exit;
			}
			$stmt->bind_param('ss', $user_name, $hashed_password);
			$stmt->execute();
			$stmt->close();
			echo "<p class='warning_message'>Signup Success! Redirecting to Login...</p>";
			header("refresh:2; url=login_check.php");
		}





		?>

	</div>




</body>

</html>