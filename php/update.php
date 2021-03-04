<?php
	require('dbconnect.php');
	session_start();

	if (isset($_POST["content"]) && isset($_POST["id"])) {
		$content = $_POST["content"];
		$id = $_POST["id"];

		$sql = "UPDATE content SET text='$content' WHERE id='$id'";
		$result = mysqli_query($connection, $sql);

		echo $id;

		header("Location: ../index.php");
	}

?>