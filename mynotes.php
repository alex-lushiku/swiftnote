<html lang="en">
	
	<?php 
		session_start(); 
		$_SESSION["page"] = "mynotes";
	?>

	<head>
		<title>Swiftnote - A minimalistic, free online text editor for your notes</title>
		
		<meta charset="utf-8"/>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="description" content="Swiftnote is a free, minimalistic, online text editor for quickly writing down, saving and sharing your notes."/>

		<link type="text/css" rel="stylesheet" href="style.css"/>

		<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
		<script type="text/javascript" src="filesaver/filesaver.js"></script>

		<link href="https://fonts.googleapis.com/css?family=Khula|Roboto|Open+Sans|Barrio|Cairo|Cantarell|Heebo|Lato|Open+Sans|PT+Sans" rel="stylesheet">
		<script src="https://ajax.googleapis.com/ajax/libs/webfont/1.6.26/webfont.js"></script>

		<!-- Bootstrap -->
		<link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap.min.css">

		<!-- Button Themes -->
		<link rel="stylesheet" type="text/css" href="buttons/btn-light-primary.css">
		<link rel="stylesheet" type="text/css" href="buttons/btn-light-default.css">
		<link rel="stylesheet" type="text/css" href="buttons/btn-dark-primary.css">
		<link rel="stylesheet" type="text/css" href="buttons/btn-dark-default.css">
		<link rel="stylesheet" type="text/css" href="buttons/btn-color-primary.css">
		<link rel="stylesheet" type="text/css" href="buttons/btn-color-default.css">
		
		<script src="bootbox/bootbox.min.js"></script>

		<!-- Fonts -->
		<script>
			WebFont.load({
				google: {
					families: ['Cantarell', "Open Sans"]
				}
			});
		</script>
	</head>

	<body onload="enableEdit('light'); handlePaste(); handleDrop(); createCookie(); showSaved(); textfield.document.designMode='off'">

		<!-- Page -->
		<div id="page">
			<!-- Top bar -->
			<?php 
				if (!isset($_SESSION["email"]))
					echo '<a href="#"><div id="topbar" onclick="popupForm();"><p id="topbar-item">Login / Register</p></div></a>';
				else
					echo '<a href="#"><div id="topbar" onclick="logout();"><p id="topbar-item">Logout</p></div></a>';
			?>

			<!-- Textfield -->
			<iframe class="textfield" name="textfield" frameBorder="0"></iframe>
			
			<!-- Toolbar -->
			<div id="toolbar">
				<a href="index.php"><img src="img/notepad_light.png" alt="My notes" class="button theme" id="btn_notes" data-toggle="tooltip" data-placement="left" title="New Note"></a>

				<h1 class="button" id="btn_bold" onclick="bold();" onmouseenter="onMouseEnter('btn_bold');" onmouseleave="onMouseLeave('btn_bold');"><b>B</b></h1>
				<h1 class="button" id="btn_ita" onclick="italic();" onmouseenter="onMouseEnter('btn_ita');" onmouseleave="onMouseLeave('btn_ita');"><i>I</i></h1>
				<h1 class="button" id="btn_und" onclick="underline();" onmouseenter="onMouseEnter('btn_und');" onmouseleave="onMouseLeave('btn_und');"><u>U</u></h1>
				
				<img src="img/theme_light2.png" alt="Change theme" class="button theme" id="btn_theme" onclick="changeTheme();" onmouseenter="onMouseEnter('btn_theme');" onmouseleave="onMouseLeave('btn_theme')"; data-toggle="tooltip" data-placement="left" title="Theme"></img>	

				<img src="img/save.png" type="submit" id="btn_save" name="save" onclick="post();" class="button theme" alt="Save note." onmouseenter="onMouseEnter('btn_save');" onmouseleave="onMouseLeave('btn_save')" data-toggle="tooltip" data-placement="left" title="Save"/>

				<img src="img/cloud_dark.png" type="button" id="btn_down" onclick="download();" class="button theme" alt="Download note" onmouseenter="onMouseEnter('btn_down');" onmouseleave="onMouseLeave('btn_down')" data-toggle="tooltip" data-placement="left" title="Download"/>
				
				<h1 class="button" id="btn_plus" onmousedown="changeFontSize(3);" onmouseenter="onMouseEnter('btn_plus');" onmouseleave="onMouseLeave('btn_plus');">+</h1>
				<h1 class="button" id="btn_minus" onmousedown="changeFontSize(-3);" onmouseenter="onMouseEnter('btn_minus');" onmouseleave="onMouseLeave('btn_minus');">-</h1>
			</div>

			<div id="result"></div>

		</div>

		<?php
			require("php/dbconnect.php");	

			if (isset($_SESSION["email"]) && isset($_SESSION["uid"])) {
				$email = $_SESSION["email"];
				$uid = $_SESSION["uid"][0];
				echo "<script>alert($uid);</script>";

				$result = mysqli_query($connection, "SELECT id FROM saved WHERE uid='$uid'");

				if (mysqli_num_rows($result) > 0)
					$saved_id = mysqli_fetch_all($result, MYSQLI_ASSOC);
				else
					$saved_id = array();

				$result = mysqli_query($connection, "SELECT name FROM saved WHERE uid='$uid'");

				if (mysqli_num_rows($result) > 0)
					$saved_name = mysqli_fetch_all($result, MYSQLI_ASSOC);
				else
					$saved_name = array();
			
			} else {
				$saved_id = unserialize($_COOKIE["cookie_notes"]);
				$saved_name = unserialize($_COOKIE["cookie_notes"]);
			}

			// Error checking
			if (isset($_SESSION["error"])) {
				$error = $_SESSION["error"];
				echo "<script>alert('$error');</script>";
				unset($_SESSION["error"]);
			}

			if (isset($_SESSION["popup"])) {
				echo "<script>$(window).on('load', function () { popupForm (); changeTheme (); changeTheme (); changeTheme (); });</script>";
				unset($_SESSION["popup"]);
			}
		?>

		<!-- Facebook Login -->
		<?php
			require 'facebook/vendor/facebook/graph-sdk/src/Facebook/autoload.php';

			$fb = new Facebook\Facebook([
			  	'app_id' => '255463098305502',
			  	'app_secret' => 'd3d44f6516f3c2a888630541ab39bafa',
				'default_graph_version' => 'v2.2',
			]);

			$helper = $fb->getRedirectLoginHelper();

			if (isset($_GET["state"]))
				$helper->getPersistentDataHandler()->set('state', $_GET['state']);

			$permissions = ['email'];
			$loginUrl = $helper->getLoginUrl('http://www.swift-note.com/facebook/vendor/login-callback.php', $permissions);
		?>

		<div id="saved_id" style="display:none;" data-info="<?php for ($i = 0;$i < count($saved_id);$i++) { echo $saved_id[$i]['id']; echo ','; } ?>"></div>
		<div id="saved_name" style="display:none;" data-info="<?php for ($i = 0;$i < count($saved_name);$i++) { print_r($saved_name[$i]['name']); echo ','; } ?>"></div>

		<script src="bootstrap/js/bootstrap.min.js"></script>
		<script src="bootbox/bootbox.min.js"></script>

		<script type="text/javascript">
			var fb_login_url = "<?php echo htmlspecialchars($loginUrl); ?>";
		</script>
		<script type="text/javascript" src="script.js"></script>

	</body>
	
</html>