<?php

// Last.fm API UTC
date_default_timezone_set('UTC');

$api_key = "";

// parse options
$username = isset($_GET["username"]) ? $_GET["username"] : "iDrago";
$size = isset($_GET["size"]) ? $_GET["size"] : "big";
$autorefresh = isset($_GET["autorefresh"]) && ($_GET["autorefresh"] == "no") ? false : true;
$color = isset($_GET["color"]) ? $_GET["color"] : "red";
$nowplaying = '<img src="images/np.gif"> En ce moment';
$lastplayed = 'Il y a ';

// headers
header('X-Frame-Options: GOFORIT'); 
header('Content-Type: text/html; charset=utf-8');

include __DIR__ . "/class.lastfm-nowplaying.php";

try {
	$np = new lastfm_nowplaying($api_key, $size);
	$track = $np->info($username);
} catch (exception $e) {
	printf("error %s", $e);
}

?>
