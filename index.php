<html lang="en">

	<?php 
		session_start();
		$_SESSION["page"] = "index";
	?>

	<head>
		<title>Swiftnote - A minimalistic, free online text editor for your notes</title>
		
		<meta charset="utf-8"/>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="description" content="Swiftnote is a free, minimalistic, online text editor for quickly writing down, saving and sharing your notes."/>

		<link type="text/css" rel="stylesheet" href="style.css"/>

		<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
		<script type="text/javascript" src="filesaver/FileSaver.js"></script>

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

		<!-- JavaScript -->
		<script src="bootstrap/js/bootstrap.min.js"></script>
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

	<body onload="enableEdit('light'); handlePaste(); handleDrop(); retrieveNote(); createCookie();">

		<!-- Facebook & Twitter Share -->
		<div id="fb-root"></div>
		<script>(function(d, s, id) {
			var js, fjs = d.getElementsByTagName(s)[0];
			if (d.getElementById(id)) return;
				js = d.createElement(s); js.id = id;
				js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.9";
				fjs.parentNode.insertBefore(js, fjs);
			}(document, 'script', 'facebook-jssdk'));
		</script>

		<div id="social">
			<?php 
				if (isset($_GET['id']))
					$id = $_GET['id'];
				else
					$id = '0';

				echo '<div class="fb-share-button" data-href="http://www.swift-note.com/index.php?id=$id" data-layout="button_count" data-size="small" data-mobile-iframe="true"><a class="fb-xfbml-parse-ignore" target="_blank" href="http://www.swift-note.com/index.php?id=$id" style="vertical-align:top;zoom:1;*display:inline">Share</a></div>';
			?>

			<a class="twitter-share-button" href="https://twitter.com/intent/tweet"></a>
			<script async src="//platform.twitter.com/widgets.js" charset="utf-8"></script>
		</div>

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
				<a href="mynotes.php"><img src="img/notepad_light.png" alt="My notes" class="button theme" id="btn_notes" data-toggle="tooltip" data-placement="left" title="My Notes"></a>

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

			$id = 0;
			$idvar = $_GET['id'];

			if ($idvar != null) {
				$sel = "SELECT text from content WHERE id = $idvar";
			} else {
				$sel = "SELECT text from content WHERE id = 0";
			}

			$result = mysqli_query($connection, $sel);

			if (isset($_SESSION["uid"]))
				$uid = $_SESSION["uid"];

			$sql = "SELECT * FROM content WHERE id = $idvar AND uid!='$uid' AND public='false'";
			$res = mysqli_query($connection, $sql);

			if (mysqli_num_rows($result) == 1) {
				while ($r = mysqli_fetch_array($result)) {
					if (mysqli_num_rows($res) == 0) {
						$content = str_replace('"', '&quot;', $r["text"]);
					} else {
						$content = "This note is private.";
						$designMode = 'off';
					}
				}
			} else {
				if ($idvar != "" && $idvar != undefined && $idvar != null)
					$content = "Unable to find note with id: $idvar";
				else 
					$content = "";
			}

			$result = mysqli_query($connection, "SELECT id FROM content ORDER BY id DESC LIMIT 1;");

			$lastid;
			if (mysqli_num_rows($result) > 0)
				$lastid = mysqli_fetch_row($result);
			else
				$lastid = 1;

			// Check if note belongs to you
			if (isset($_SESSION["uid"])) {
				if ($idvar <= $lastid) {
					if ($idvar == "0" || $idvar == "") {
						$designMode = 'on';
					} else {
						if (is_numeric((int) $idvar)) {
							$uid = $_SESSION["uid"];
							$check = "SELECT uid FROM saved WHERE id='$idvar'";
							$resu = mysqli_query($connection, $check);

							if (!mysqli_num_rows($resu) > 0) {
								$designMode = 'off';
							} else {
								if ($uid == mysqli_fetch_row($resu)[0])
									$designMode = 'on';
								else
									$designMode = 'off';
							}
						} else {
							$designMode = 'off';
						}
					}
				} else {
					$designMode = 'off';
				}

				echo $designMode;
			} else {
				if ($idvar <= $lastid && is_numeric($idvar)) {
					if (isset($_COOKIE["cookie_notes"])) {
						$cookie = unserialize($_COOKIE["cookie_notes"]);
						$arr = array();
						
						for ($i = 0;$i < count($cookie);$i++)
							array_push($arr, $cookie[$i]['id']);

						for ($i = 0;$i < count($arr);$i++) {
							if ($arr[$i] == (int) $idvar) {
								$designMode = 'on';
								break;
							} else {
								$designMode = 'off';
							}
						}
					} else {
						$designMode = 'off';
					}
				} else if ($idvar == "0" || $idvar == "") {
					$designMode = 'on';
				} else {
					$designMode = 'off';
				}
			}

			// Error checking
			if (isset($_SESSION["error"])) {
				$error = $_SESSION["error"];
				echo "<script>$(window).on('load', function() { alert('$error'); changeTheme (); changeTheme (); changeTheme ();});</script>";
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

		<div id="hid" style="display:none;" data-info="<?php echo $content; ?>"></div>

		<script type="text/javascript">
			var designMode = "<?php echo $designMode; ?>";
			var fb_login_url = "<?php echo htmlspecialchars($loginUrl); ?>";

			$(document).ready(function () {
				textfield.document.designMode = designMode;
			});	

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

			function post() {
				var name;

				if (!saved) {
					var content = getContent();
					var id = "<?php echo $idvar ?>";
					var lastid = "<?php echo $lastid[0]; ?>";
					var btn_theme;

					if (getCookie("theme") == 1) {
						btn_theme = "btn-dark-primary";
					} else if (getCookie("theme") == 2) {
						btn_theme = "btn-color-primary";
					} else {
						btn_theme = "btn-light-primary";
					}

					if (id == 0 || id == undefined || id == null) {
						$.post("php/note.php", {posttext:content}, function (data) {});

						var loggedIn = "<?php if (isset($_SESSION['email'])) { echo $_SESSION['email']; } else { echo ""; } ?>";
						var form;

						if (loggedIn == null || loggedIn == undefined || loggedIn == "")
							form = '<form id="form" action="php/saved.php"><div class="form-group"><input type="text" class="form-control" name="name" style="color:' + color_textfield_text + ';border:1px solid ' + color_border + ';background:' + color_textfield + ';" autofocus><br><label class="radio-inline"><input type="radio" name="publicity" checked>Public</label><label class="radio-inline"><input type="radio" name="publicity" disabled>Private</label></div></form>';
						else
							form = '<form id="form" action="php/saved.php"><div class="form-group"><input type="text" class="form-control" name="name" style="color:' + color_textfield_text + ';border:1px solid ' + color_border + ';background:' + color_textfield + ';" autofocus><br><label class="radio-inline"><input type="radio" name="publicity" checked>Public</label><label class="radio-inline"><input type="radio" name="publicity">Private</label></div></form>';

						bootbox.confirm({
							message: '<h4>Name your note</h4>' + form, 
							buttons: {
								'cancel': {
									label: 'Cancel',
									className: color_btn_default
								}, 
								'confirm': {
									label: 'Save',
									className: color_btn_primary
								}
							},
							callback: function(result) {

							if (result) {
								if (content != "") {
									increment++;
									lastid = parseInt(lastid) + increment;
									document.cookie = "saved=" + document.cookie + lastid + " " + ";expires=Date.getTime() + 60 * 60 * 24 * 365 * 10;";

									bootbox.dialog({
										message: "<strong>Note saved at:</strong><br><a style='color:" + color_text + "' href='index.php?id=" + lastid + "'>http://www.swift-note.com/index.php?id=" + lastid + "</a>", 
										backdrop: true, 
										onEscape: true,
										buttons: {
											'succes': {
												label: 'OK',
												className: 'btn ' + btn_theme
											}
										}
									}).find('.modal-content').css({'background-color': color_modal, 'color' : color_text});
									
									document.getElementById("btn_save").style.opacity = "0.3";
									document.getElementById("btn_save").title = "Saved at:\nswift-note.com/index.php?id=" + lastid;
									saved = true;
								} else {
									bootbox.alert({
										message: "Empty note",
										className: "bb-alternate-modal"
									});
								}

								name = $('#form').find("input[name='name']").val().replace(/,/g,'');
								var publicity = $('#form').find("input[name='publicity']:checked").parent('label').text();

								if (name == "" || name == undefined || name == null)
									name = "Untitled";

								$.post("php/saved.php", {name:name}, function (data) {});
								$.post("php/note.php", {publicity:publicity}, function (data) {});
							}
						}}).find('.modal-content').css({'background-color': color_modal, 'color' : color_text});
					} else {
						// Edit Note
						var message = "lol";
						if (parseInt(id) > parseInt(lastid) || isNaN(id)) {
							message = "Unable to find note with id: " + id;
						} else {
							message = "Note successfully edited!";
							$.post("php/update.php", {content:content, id:id}, function (data) {});
						}

						document.getElementById("btn_save").style.opacity = "0.3";
						document.getElementById("btn_save").title = "Saved at:\nswift-note.com/index.php?id=" + lastid;
						saved = true;

						bootbox.dialog({
							message: message,
							backdrop: true, 
							onEscape: true,
							buttons: {
								'succes': {
									label: 'OK',
									className: 'btn ' + btn_theme
								}
							}
						}).find('.modal-content').css({'background-color': color_modal, 'color' : color_text});
					}
				}
			}
		</script>

		<script src="bootstrap/js/bootstrap.min.js"></script>
		<script src="bootbox/bootbox.min.js"></script>

		<script type="text/javascript" src="script.js"></script>

		<!-- Google Analytics -->
		<script>
		  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
		  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
		  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
		  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

		  ga('create', 'UA-104668467-1', 'auto');
		  ga('send', 'pageview');
		</script>

	</body>
	
</html>