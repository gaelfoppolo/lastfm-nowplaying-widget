<?php

include __DIR__ . "/class/LastFM_NowPlaying.php";

// Last.fm API UTC
date_default_timezone_set('UTC');

// options to complete

function getOptions(){

}

$api_key = "c3a41cdadfe269b2082b12eac19ec77f";
$username = "iDrago";

try {

	$nowplaying = new LastFM_NowPlaying($username,$api_key);
	$track = $nowplaying->info();
	header('Content-Type: text/plain; charset=utf-8');
	echo json_encode($track, JSON_PRETTY_PRINT);

} catch (Exception $e) {
	printf("%s", $e);
}

?>
