<?php

/* 
 * A Last.fm now playing widget
 * (c) 2012 Callum Jones, 2015 Gaël Foppolo
 * <cj@icj.me>, <me@gaelfoppolo.com>
 */

include __DIR__ . "/class/LastFM_NowPlaying.php";

// Last.fm API UTC
date_default_timezone_set('UTC');

// options to complete

$api_key = "";
$username = "";

try {

	$nowplaying = new LastFM_NowPlaying($username,$api_key);
	$track = $nowplaying->info();
	header('Content-Type: text/plain; charset=utf-8');
	echo json_encode($track, JSON_PRETTY_PRINT);

} catch (Exception $e) {

	printf("%s", $e);

}

?>
