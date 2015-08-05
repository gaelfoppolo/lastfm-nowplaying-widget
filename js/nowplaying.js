window.addEventListener("load", function(){

	var color = "red";
	var size = "small";

	function createDOM() {

		var lastfm = document.querySelector("#lastfm");
		lastfm.setAttribute("class", size);

		var topbar = document.createElement("div");
		topbar.setAttribute("id", "topbar");
		topbar.setAttribute("class", color);

		lastfm.appendChild(topbar);

		var img_np = document.createElement("img");
		img_np.setAttribute("id","nowplaying")
		img_np.setAttribute("src","images/np.gif");

		var titlebar = document.createElement("titlebar");
		titlebar.innerHTML = "Chargement...";

		topbar.appendChild(img_np);
		topbar.appendChild(titlebar);

		var img_artwork = document.createElement("img");
		img_artwork.setAttribute("id","artwork");
		img_artwork.setAttribute("src","images/no_artwork.png");

		lastfm.appendChild(img_artwork);

		var songinfo = document.createElement("div");
		songinfo.setAttribute("id","songinfo");

		lastfm.appendChild(songinfo);

		var song = document.createElement("song");

		songinfo.appendChild(song);

		var songname = document.createElement("name");

		var loved = document.createElement("loved");

		song.appendChild(songname);
		song.appendChild(loved);

		var artist = document.createElement("artist");

		songinfo.appendChild(artist);

		var album = document.createElement("album");

		songinfo.appendChild(album);

		var userinfo = document.createElement("div");
		userinfo.setAttribute("id","userinfo");

		lastfm.appendChild(userinfo);

		var duration = document.createElement("duration");

		userinfo.appendChild(duration);

		var note = document.createElement("note");
		var time = document.createElement("time");

		duration.appendChild(note);
		duration.appendChild(time);

		var playcount = document.createElement("playcount");

		userinfo.appendChild(playcount);

		var user = document.createElement("user");

		userinfo.appendChild(user);

		var link_user = document.createElement("a");
		link_user.setAttribute("target","_blank");
		link_user.setAttribute("href","");

		user.appendChild(link_user);

	}

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
		var time = document.querySelector("time");
		time.innerHTML = data.duration;
		var note = document.querySelector("note");
		note.innerHTML = "&#9835;";
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

	createDOM();

	requestData(updateNowPlaying);

	setInterval(requestData,60000,updateNowPlaying);

});