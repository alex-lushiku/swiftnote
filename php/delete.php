<?php
	require("dbconnect.php");

	session_start();

	if (isset($_POST["id"])) {
		$id = $_POST["id"];
		
		if (isset($_SESSION["email"])) {
			$sql_content = "DELETE FROM content WHERE id='$id'";
			mysqli_query($connection, $sql_content);

			$sql_saved = "DELETE FROM saved WHERE id='$id'";
			mysqli_query($connection, $sql_saved);
		} else {
			$cookie_id = unserialize($_COOKIE["cookie_ids"]);
			$cookie_names = unserialize($_COOKIE["cookie_names"]);
			
			for ($i = 0;$i < count($cookie_id);$i++) {
				if ($cookie[$i][0] == $id) {
					unset($cookie_id[$i]);
					unset($cookie_names[$i]);
					setcookie("cookie_ids", serialize(array_values($cookie_id)), time() + (365 * 24 * 60 * 60));
					setcookie("cookie_names", serialize(array_values($cookie_names)), time() + (365 * 24 * 60 * 60));
					break;
				}
			}
		}
	}
?>