<html>

	<head>
		<title>Reset Password</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="description" content="Swiftnote is a free, minimalistic, online text editor for quickly writing down, saving and sharing your notes."/>

		<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

		<link rel="stylesheet" type="text/css" href="../bootstrap/css/bootstrap.min.css">
		<link rel="stylesheet" type="text/css" href="../buttons/btn-light-primary.css">
		<link rel="stylesheet" type="text/css" href="../buttons/btn-light-default.css">
		<link rel="stylesheet" type="text/css" href="../buttons/btn-dark-primary.css">
		<link rel="stylesheet" type="text/css" href="../buttons/btn-dark-default.css">
		<link rel="stylesheet" type="text/css" href="../buttons/btn-color-primary.css">
		<link rel="stylesheet" type="text/css" href="../buttons/btn-color-default.css">
	</head>

	<body onload="createCookie(); prompt();">
		
		<?php
			require('dbconnect.php');
			session_start();

			if (isset($_GET["email"]) && isset($_GET["token"])) {
				$email = mysqli_real_escape_string($connection, $_GET["email"]);
				$token = mysqli_real_escape_string($connection, $_GET["token"]);

				$sql = "SELECT token FROM users WHERE email='$email' AND token='$token' AND isSocial='0'";
				$res = mysqli_query($connection, $sql);

				if (!mysqli_num_rows($res) > 0) {
					$_SESSION["error"] = "Incorrect email or token!";
					header("Location: ../" . $_SESSION["page"] . ".php");
					exit;
				}

				if (isset($_POST["pass"]) && isset($_POST["cpass"])) {
					if ($_POST["pass"] == $_POST["cpass"]) {
						$result = mysqli_query($connection, "SELECT id FROM users WHERE email='$email' AND token='$token'");

						if (mysqli_num_rows($result) > 0) {
							$pass = password_hash($_POST["pass"], PASSWORD_DEFAULT);
							mysqli_query($connection, "UPDATE users SET pass='$pass', token='' WHERE email='$email' AND isSocial='0'");
							$_SESSION["error"] = "Password successfully reset!";
							header("Location: ../" . $_SESSION["page"] . ".php");
							exit;
						}
					} else {
						echo "<script>alert('Passwords do\'nt match!');</script>";
					}
				}
			}

		?>

		<script src="../bootstrap/js/bootstrap.min.js"></script>
		<script src="../bootbox/bootbox.min.js"></script>
		<script type="text/javascript">
			var iterator;
			var backgroundColor;
			var textColor = ["#999", "#757575", "#885B35"];
			var modalColor = ["#EEE", "#202020", "#FFF7CC"];
			var textfieldColor = ["#F4F4F4", "#545454", "#FFFAE0"];
			var textfieldTextColor = ["#999", "#999", "#885B35"];
			var borderColor = ["#BBB", "#303030", "#BBB"];
			var btn_theme;
			var form;

			function prompt() {
				bootbox.dialog({
					title: '<span style="' + textColor[iterator] + '">Enter a new password</span>',
					message: form,
					closeButton: false
				}).find('.modal-content').css({'background-color': modalColor[iterator], 'color' : textColor[iterator]});
			}

			function setBackgroundColor (color) {
				document.body.style.backgroundColor = color;
			} 

			function getCookie(cname) {
			    var name = cname + "=";
			    var ca = document.cookie.split(';');
			    for(var i = 0; i < ca.length; i++) {
			        var c = ca[i];
			        while (c.charAt(0) == ' ') {
			            c = c.substring(1);
			        }
			        if (c.indexOf(name) == 0) {
			            return c.substring(name.length, c.length);
			        }
			    }
			    return "";
			}

			function createCookie() {
				if (getCookie("theme") == "")
					iterator = 0;
				else
					iterator = getCookie("theme");

				if (iterator == 0) {
					backgroundColor = "#EEE";
					btn_theme = "btn-light-primary";
				} else if (iterator == 1) {
					backgroundColor = "#151515";
					btn_theme = "btn-dark-primary";
				} else {
					backgroundColor = "#FFF7CC";
					btn_theme = "btn-color-primary";
				}

				form = '<form method="POST"> <label style="color:' + textColor[iterator] + '">New password</label> <div class="form-group"> <input style="color:' + textfieldTextColor[iterator] + ';background:' + textfieldColor[iterator] + ';border: 1px solid ' + borderColor[iterator] + ';" type="password" placeholder="New password" name="pass" class="form-control" required> </div> <label style="color:' + textColor[iterator] + '">Confirm new password</label> <div class="form-group"> <input style="color:' + textfieldTextColor[iterator] + ';background:' + textfieldColor[iterator] + ';border: 1px solid ' + borderColor[iterator] + ';"type="password" placeholder="Confirm new password" name="cpass" class="form-control" required> </div> <button type="submit" class="btn ' + btn_theme + ' btn-block">Reset</button></form>';

				setBackgroundColor(backgroundColor);
			}

		</script>

	</body>

</html>