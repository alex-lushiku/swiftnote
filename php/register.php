<?php
	require("dbconnect.php");
	session_start();

	if (isset($_GET["email"]) && isset($_GET["password"])) {
		$email = mysqli_real_escape_string($connection, $_GET["email"]);
		$password = mysqli_real_escape_string($connection, $_GET["password"]);

		$sql = "INSERT INTO users (email, pass, isSocial) VALUES ('$email', '$password', '0')";
		mysqli_query($connection, $sql);
		$_SESSION["email"] = $email;

		$sql = "SELECT id FROM users WHERE email='$email' AND isSocial='0'";
		$result = mysqli_query($connection, $sql);

		$_SESSION["uid"] = mysqli_fetch_row($result);

		if (isset($_SESSION["page"]))
			$page = $_SESSION["page"];
		else 
			$page = "index";

		header("Location: ../$page.php");
		exit;
	}
?>