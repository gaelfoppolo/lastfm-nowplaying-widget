window.addEventListener("load", function(){

	var color = "red";
	var size = "big";

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

		var title = document.querySelector("titlebar");
		title.innerHTML = is_too_long(data.title,30);
		//artwork
		var artwork = document.querySelector("#artwork");
		artwork.src = data.image;
		//song name
		var songname = document.querySelector("name");
		songname.innerHTML = is_too_long(data.name,23);
		//artist name
		var artiste = document.querySelector("artist");
		artiste.innerHTML = is_too_long(data.artist,23);
		//album name
		var album = document.querySelector("album");
		album.innerHTML = is_too_long(data.album,23);
		//duration
		var duration = document.querySelector("time");
		duration.innerHTML = data.duration;
		//playcount
		var playcount = document.querySelector("playcount");
		playcount.innerHTML = data.playcount;		
		//loved song
		var loved = document.querySelector("loved");
		loved.innerHTML = (typeof data.userloved != 'undefined') ? data.userloved : "";
		//username
		var username = document.querySelector("user > a");
		username.innerHTML = data.username;
		username.href = "http://www.last.fm/user/"+data.username;

	}

	function is_too_long(string,length) {

		return (string.length >= length) ? create_marquee(string) : string;

	}

	function create_marquee(string) {

		var marquee = document.createElement("marquee");
		marquee.setAttribute("direction", "left");
		marquee.setAttribute("behavior", "scroll");
		marquee.setAttribute("scroll", "on");
		marquee.setAttribute("scrollamount", "3");
		marquee.innerHTML = string;

		return marquee.outerHTML;

	}

});