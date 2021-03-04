<?php
	require("dbconnect.php");
	session_start();

	$log_email = $_POST["log_email"];
	$log_pass = $_POST["log_password"];

	$sql = "SELECT * FROM users WHERE email='$log_email'";
	$result = mysqli_query($connection, $sql);

	$row = mysqli_fetch_assoc($result);
	$hash_pass = $row['pass'];
	$hash = password_verify($log_pass, $hash_pass);

	$sql = "SELECT * FROM users WHERE email='$log_email' AND pass='$hash_pass'";
	$result = mysqli_query($connection, $sql);

	if ($hash == 0) {
		$_SESSION["error"] = "Email and password don\'t match!";
		$_SESSION["popup"] = true;
		header("Location: ../" . $_SESSION["page"] . ".php");
		exit;
	} else {
		if ($row = mysqli_fetch_assoc($result)) {
			$_SESSION["email"] = $log_email;

			$sql = "SELECT id FROM users WHERE email='$log_email' AND isSocial='0'";
			$result = mysqli_query($connection, $sql);

			$_SESSION["uid"] = mysqli_fetch_row($result);
		}
	}

	header("Location: ../" . $_SESSION["page"] . ".php");
	exit;
?>