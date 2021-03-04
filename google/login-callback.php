<!-- Google+ Login -->
<?php
	require "gplus-lib/vendor/autoload.php";
	session_start();

	const CLIENT_ID = "499341419229-abpnk7osoml0uq78bdctc6k4u0ao8nqn.apps.googleusercontent.com";
	const CLIENT_SECRET = "6TYgKllS9dps8om96gRb9h7t";
	const REDIRECT_URI = "http://localhost/register-social.php";

	$client = new Google_Client();
	$client->setClientId(CLIENT_ID);
	$client->setClientSecret(CLIENT_SECRET);
	$client->setRedirectUri(REDIRECT_URI);
	$client->setScopes('email');

	$google = new Google_Service_Plus($client);

	if (isset($_GET["code"])) {
		$client->authenticate($_GET["code"]);
		$_SESSION["google_access_token"] = $client->getAccessToken();
		header ("Location: http://localhost/" + $_SESSION["page"] . ".php");
		exit;
	}

	if (isset($_SESSION["google_access_token"])) {
		$client->setAccessToken($_SESSION["google_access_token"]);
		$me = $google->people->get("me");

		$email = $me["emails"][0]["value"];
		$_SESSION["email"] = $email;
	} else {
		$authUrl = $client->createAuthUrl();
	}
?>