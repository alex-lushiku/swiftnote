<?php 
	session_start();
	require_once __DIR__. '../facebook/vendor/autoload.php';

	$facebook = new Facebook\Facebook([
		'app_id' => '255463098305502',
		'app_secret' => 'd3d44f6516f3c2a888630541ab39bafa',
		'default_graph_version' => 'v2.6',
		'persistent_data_handler' => 'session'
	]);

	$helper = $facebook->getRedirectLoginHelper();
	$permissions = ['email'];
	$loginUrl = $helper->getLoginUrl('http://www.swift-note.com/facebook/php-graph-sdk/login-callback.php', $permissions);
?>