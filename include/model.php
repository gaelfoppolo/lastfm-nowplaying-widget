<?php

include __DIR__ . "/LastFM_NowPlaying.php";

// Last.fm API UTC
date_default_timezone_set('UTC');

// headers
header('X-Frame-Options: GOFORIT'); 
header('Content-Type: text/html; charset=utf-8');

// options

$api_key = "";

$username = isset($_GET["username"]) ? $_GET["username"] : "iDrago";
$size = isset($_GET["size"]) ? $_GET["size"] : "big";
$color = isset($_GET["color"]) ? $_GET["color"] : "red";

try {

	$nowplaying = new LastFM_NowPlaying($size,$color,$username,$api_key);
	$track = $nowplaying->info();
	//header('Content-Type: text/plain');
	//echo json_encode($track, JSON_PRETTY_PRINT);

} catch (exception $e) {
	printf("%s", $e);
}



?>
