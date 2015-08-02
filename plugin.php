<?php include __DIR__ . "/include/model.php"; ?>
<!doctype html>
<html>
<head>
	<title><?php echo $username; ?> on last.fm</title>
	<link rel="stylesheet" type="text/css" href="styles/last.fm.css">	
</head>
<body>
	<div id="lastfm" class="<?php echo $size; ?>">
		<div id="topbar" class="<?php echo $color; ?>">
			<img src="images/np.gif"> <?= $track['title']; ?>
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
			<duration><strong>&#9835;</strong> <?php echo $track['duration']; ?></duration>
			<playcount><?php echo $track['playcount']; ?></playcount>
			<user><a target="_blank" href="http://www.last.fm/user/<?php echo $username; ?>"><?php echo $username; ?></a></user>
		</div>
	</div>
</body>
</html>