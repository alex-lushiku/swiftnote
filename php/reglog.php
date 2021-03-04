<?php session_start(); session_destroy() ?>

<html>
	
	<head>
		<title>Register</title>

		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
	</head>

	</body>

		<div>

			<form action="register.php" method="POST">
				<h1>Register</h1>

				<label for="email">Email Address</label><br>
				<input type="email" name="email" placeholder="Email Adress" required>
				<br><br>

				<label for="email">Password</label><br>
				<input type="Password" name="pass" placeholder="Password" required>
				<br><br>

				<label for="email">Confirm Password</label><br>
				<input type="Password" name="cpass" placeholder="Confirm Password" required>
				<br><br>

				<button type="submit">Register</button>
			</form>

			<br>
			
			<form action="login.php" method="POST">
				<h1>Login</h1>

				<label for="email">Email Address</label><br>
				<input type="email" name="email" placeholder="Email Address" required>
				<br><br>

				<label for="email">Password</label><br>
				<input type="Password" name="pass" placeholder="Password" required>
				<br><br>

				<button type="submit">Login</button>
			</form>

			<?php
				if (isset($_SESSION["message"]))
					echo $_SESSION["message"];
			?>
			
			<form action="logout.php">
				<button type="submit">Logout</button>
			</form>
		</div>	

	</body>

</html>