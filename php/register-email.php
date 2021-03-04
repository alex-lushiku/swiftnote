<?php
	require "dbconnect.php";
	require_once("../PHPMailer/PHPMailerAutoload.php");
	session_start();

	if (isset($_POST["reg_email"])) {
		$email = $_POST["reg_email"];
		$password = $_POST["reg_password"];
		$confirm_password = $_POST["reg_confirm_password"];

		if ($password == $confirm_password) {
			$hash = password_hash($password, PASSWORD_DEFAULT);

			if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
			 	$sql = "SELECT email FROM users WHERE email='$email' AND isSocial='0'";
				$result = mysqli_query($connection, $sql);

				if (!mysqli_num_rows($result) > 0) {
					$from = "noreply@swift-note.com";
					$url = "http://www.swift-note.com/php/register.php?&email=$email&password=$hash";

					// Setup SMTP
					$mail = new PHPMailer();
					$mail->IsSMTP();
					$mail->SMTPDebug = 1;
					$mail->SMTPAuth = true;
					$mail->SMTPSecure = 'ssl';
					$mail->Host = "smtp.swift-note.com";
					$mail->Port = 465;
					$mail->IsHTML(true);
					$mail->Username = $from;
					$mail->Password = "Fa$L0SJi6hDtN7Hc";
					$mail->SetFrom($from);
					$mail->Subject = "Verify email address";
					$mail->Body = "";
					$mail->AddAddress($email);
					if (!$mail->Send()) 
						echo $mail->ErrorInfo;

					//mail($email, "Verify email address", "Click on this link to verify your email address:\n$url", "From: $from\r\n");

					$_SESSION["error"] = "An email has been sent to $email!";
					header("Location: ../" . $_SESSION["page"] . ".php");
					exit;
				} else {
					$_SESSION["error"] = "Email already registered!";
					$_SESSION["popup"] = true;
					header("Location: ../" . $_SESSION["page"] . ".php");
					exit;
				}
			} else {
				$_SESSION["error"] = "Invalid email address"; 
			  	$_SESSION["popup"] = true;
			  	header("Location: ../" . $_SESSION["page"] . ".php");
				exit;
			}
		} else {
			$_SESSION["error"] = "Passwords don't match!";
			$_SESSION["popup"] = true;
			header("Location: ../" . $_SESSION["page"] . ".php");
			exit;
		}
	}

?>