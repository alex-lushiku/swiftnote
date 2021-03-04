<?php
	require("dbconnect.php");

	session_start();

	if (mysqli_connect_errno()) {
		echo "Failed to connect to MySQL:" . mysqli_error();
	}

	if (isset($_POST["posttext"]) && !empty($_POST["posttext"])) {
		$content = $_POST['posttext'];
		$content = mysqli_real_escape_string($connection, $_POST["posttext"]);

		$insert = "INSERT INTO content (text) VALUES ('$content')";
		mysqli_query($connection, $insert);

		$_SESSION["lastid"] = mysqli_insert_id($connection);
	}

	if (isset($_POST["publicity"])) {
		$publicity = $_POST["publicity"];
		$isPublic = 1;

		if ($publicity == "Private")
			$isPublic = 0;

		if (isset($_SESSION["uid"]))
			$uid = $_SESSION["uid"];
		else
			$uid = NULL;

		$id = $_SESSION["lastid"];

		$sql = "UPDATE content SET public=$isPublic, uid='$uid' WHERE id='$id'";
		mysqli_query($connection, $sql);
	}

?>