window.addEventListener("load", function(){

	requestData(updateNowPlaying);

	setInterval(requestData,60000,updateNowPlaying);

	function requestData(callBack){
		var httpRequest = new XMLHttpRequest();
			httpRequest.open("GET", "nowplaying.php", true);
			httpRequest.addEventListener("load", function () {
		    	callBack(httpRequest);
			});
			httpRequest.send(null);
	}

	function updateNowPlaying(req) {
		var data = JSON.parse(req.responseText);
		//console.log(data);
		var title = document.querySelector("titlebar");
		title.innerHTML = data.title;
		//artwork
		var artwork = document.querySelector("#artwork");
		artwork.src = data.image;
		//song name
		var songname = document.querySelector("name");
		songname.innerHTML = data.name;
		//loved song
		var loved = document.querySelector("loved");
		loved.innerHTML = (typeof data.userloved != 'undefined') ? data.userloved : "";
		//artist name
		var artiste = document.querySelector("artist");
		artiste.innerHTML = data.artist;
		//album name
		var album = document.querySelector("album");
		album.innerHTML = data.album;
		//duration
		var duration = document.querySelector("time");
		duration.innerHTML = data.duration;
		//playcount
		var playcount = document.querySelector("playcount");
		playcount.innerHTML = data.playcount;
		//username
		var username = document.querySelector("user > a");
		username.innerHTML = data.username;
		username.href = "http://www.last.fm/user/"+data.username;

	}

});