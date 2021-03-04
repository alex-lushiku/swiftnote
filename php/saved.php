<?php
	require("dbconnect.php");
	session_start();

	if (mysqli_connect_errno()) {
		echo "Failed to connect to MySQL:" . mysqli_error();
	}

	if (isset($_POST["name"])) {
		$result = mysqli_query($connection, "SELECT id FROM content ORDER BY id DESC LIMIT 1;");
			
		if (mysqli_num_rows($result) > 0)
			$lastid = mysqli_fetch_row($result);

		$name = htmlentities(addslashes($_POST["name"]), ENT_QUOTES);

		// If you're logged in
		if (isset($_SESSION["email"]) && isset($_SESSION["uid"])) {
			$email = $_SESSION["email"];
			$uid = $_SESSION["uid"];

			$sql = "INSERT INTO saved (id, name, uid) VALUES ('$lastid[0]', '$name', '$uid')";			
			$result = mysqli_query($connection, $sql);

			header("Location: ../index.php");
			
		// If you're logged out
		} else {
			if (!isset($_COOKIE["cookie_ids"]) && !isset($_COOKIE["cookie_names"])) {
				$cookie_id = array(array($lastid[0]));
				$cookie_name = array(array($name));
				setcookie("cookie_ids", serialize($cookie_id), time() + (365 * 24 * 60 * 60));
				setcookie("cookie_names", serialize($cookie_name), time() + (365 * 24 * 60 * 60));
			} else {
				$cookie_id = unserialize($_COOKIE["cookie_ids"]);
				$cookie_name = unserialize($_COOKIE["cookie_names"]);
				array_push($cookie_id, array($lastid[0]);
				array_push($cookie_names, array($name);
				setcookie("cookie_ids", serialize($cookie_id), time() + (365 * 24 * 60 * 60));
				setcookie("cookie_names", serialize($cookie_name), time() + (365 * 24 * 60 * 60));
			}
		}
	}
?>