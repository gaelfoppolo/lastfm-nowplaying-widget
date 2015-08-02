<?php include __DIR__ . "/include/model.php"; ?>
<!doctype html>
<html>
<head>
	<title><?php echo $username; ?> on last.fm</title>
	<link rel="stylesheet" type="text/css" href="styles/last.fm.css">
	<style type="text/css">body { background: #<?php echo $bgcolor; ?> }</style>
	
</head>
<body>
	<div id="lastfm" class="<?php echo $size; ?> center">
		<div id="topbar" class="<?php echo $color; ?>">
			<?php echo $playing = $track['nowplaying'] ? $nowplaying : $lastplayed.$track['date']; ?> sur Last.fm
		</div>
		<?php if(!empty($track['url'])) { ?><a target="_blank" href="<?php echo $track['url']; ?>"><?php } ?>

			<img id="artwork" src="<?php echo $track['image']; ?>">
		<?php if(!empty($track['url'])) { ?></a><?php } ?>

		<div id="songinfo">
			<song><?php echo $track['name']; ?> <span class="loved"><?php echo $track['userloved']; ?></span></song>
			<artist><?php echo $track['artist']; ?></artist>
			<album><?php echo $track['album']; ?></album>
		</div>
		<div id="userinfo">
			<duration><?php echo $track['duration']; ?></duration>
			<playcount><?php echo $track['playcount']; ?></playcount>
			<user><a target="_blank" href="http://www.last.fm/user/<?php echo $username; ?>"><?php echo $username; ?></a></user>
		</div>
	</div>
</body>
</html>