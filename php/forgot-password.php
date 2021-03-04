<?php
	require('dbconnect.php');
	require_once("../PHPMailer/PHPMailerAutoload.php");
	session_start();

	if (isset($_POST["forgot_email"])) {
		$email = $_POST["forgot_email"];

		if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
			$sql = "SELECT * FROM users WHERE email='$email' AND isSocial='0'";
			$result = mysqli_query($connection, $sql);

			if (mysqli_num_rows($result) > 0) {
				$from = "noreply@swift-note.com";
				$characters = "1234567890qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM";
				$token = substr(str_shuffle($characters), 0, 10);
				$url = "http://www.swift-note.com/reset-password.php?token=$token&email=$email";

				mail($email, "Reset password", "Reset your password here:\n$url", "From: $from\r\n");

				// Upload token into database
				$sql = "UPDATE users SET token='$token' WHERE email='$email' AND isSocial='0'";
				mysqli_query($connection, $sql);

				$_SESSION["error"] = "An email has been sent to $email!";
			} else {
				$_SESSION["error"] = "This email address has not been registered yet!";
				$_SESSION["popup"] = true;
				header("Location: ../" . $_SESSION["page"] . ".php");
				exit;
			}

			header("Location: ../" . $_SESSION["page"] . ".php");
			exit;
		} else {
			$_SESSION["error"] = "Invalid email address!";
			$_SESSION["popup"] = true;
			header("Location: ../" . $_SESSION["page"] . ".php");
			exit;
		}
	}

?>