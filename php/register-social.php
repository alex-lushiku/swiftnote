<?php
	require 'dbconnect.php';
	session_start();

	if (isset($_SESSION["email"])) {
		$email = $_SESSION["email"];
	} else {
		header("Location: ../" . $_SESSION["page"] . ".php");
		exit;
	}

	$sql = "SELECT email FROM users WHERE email='$email' AND isSocial='1'";
	$result = mysqli_query($connection, $sql);

	if (!mysqli_num_rows($result) > 0) {

		$sql = "INSERT INTO users (email, isSocial) VALUES ('$email', '1')";
		mysqli_query($connection, $sql);

		// Get last inserted id from users table
		$result = mysqli_query($connection, "SELECT id FROM users ORDER BY id DESC LIMIT 1;");
	}

	$sql = "SELECT id FROM users WHERE email='$email' AND isSocial='1'";
	$result = mysqli_query($connection, $sql);

	$_SESSION["email"] = $email;
	$_SESSION["uid"] = mysqli_fetch_row($result)[0];

	header("Location: ../" . $_SESSION["page"] . ".php");
	exit;
?>