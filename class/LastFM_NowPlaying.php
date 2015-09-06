<?php

/* 
 * A Last.fm now playing widget
 * (c) 2012 Callum Jones, 2015 Gaël Foppolo
 * <cj@icj.me>, <me@gaelfoppolo.com>
 */

class LastFM_NowPlaying {

	private $api_root = "http://ws.audioscrobbler.com/2.0/";
	private $user_agent = 'Now Playing Widget';
	private $api_key;
	public $username;
	
	public function __construct($username, $api_key) {

		$this->username = $username;
		$this->api_key = $api_key;

		if(empty($api_key)) { 
			throw new exception("Please set an API key."); 
		} else if(empty($username)) { 
			throw new exception("Please set an username."); 
		}
	}

	private function retrieveData($url) {
		$ch = curl_init();
		$timeout = 5;
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
		curl_setopt($ch, CURLOPT_USERAGENT, $this->user_agent);
		
		return curl_exec($ch);
		curl_close($ch);
	}

	public function formatDateDiff($start, $end) {
	    if(!($start instanceof DateTime)) {
	        $start = new DateTime($start);
	    }
	   
	    if($end === null) {
	        $end = new DateTime();
	    }
	   
	    if(!($end instanceof DateTime)) {
	        $end = new DateTime($start);
	    }
	   
	    $interval = $end->diff($start);
	    $doPlural = function($nb,$str){return $nb>1?$str.'s':$str;}; // adds plurals
	   
	    $format = array();
	    if($interval->y !== 0) {
	        $format[] = "%y ".$doPlural($interval->y, "an");
	    }
	    if($interval->m !== 0) {
	        $format[] = "%m mois";
	    }
	    if($interval->d !== 0) {
	        $format[] = "%d ".$doPlural($interval->d, "jour");
	    }
	    if($interval->h !== 0) {
	        $format[] = "%h ".$doPlural($interval->h, "heure");
	    }
	    if($interval->i !== 0) {
	        $format[] = "%i ".$doPlural($interval->i, "minute");
	    }
	    if($interval->s !== 0 && !count($format)) {
	        return "moins d'une minute";
	    }
	   
	    // We use the two biggest parts
	    if(count($format) > 1) {
	        $format = array_shift($format)." et ".array_shift($format);
	    } else {
	        $format = array_pop($format);
	    }
	   
	    // Prepend 'since ' or whatever you like
	    return $interval->format($format);
	}

	public function info() {

		$recent_tracks = $this->retrieveData($this->api_root . "?format=json&method=user.getrecenttracks&user=" . $this->username . "&api_key=" . $this->api_key . "&limit=1");

		$recent_tracks = json_decode($recent_tracks, true);

		if(empty($recent_tracks)) {
			throw new exception("User recent tracks empty. API down? Check Last.fm API status.");
		} else if(isset($recent_tracks["error"])) {
			throw new exception($recent_tracks["message"]);
		}

		// get the users top track's information from what we can
		$track = $recent_tracks['recenttracks']['track']['0'];

		foreach($track as $key => $item) {
			if(is_array($item)) {
				if(isset($item['#text'])) {
					// put this as the key
					$track[$key] = $item['#text'];
				}
			} else {
				$track[$key] = $item;
			}
		}

		// make 'image' only the extra large one (that we want) and also add in the no artwork if its the case
		$track['image'] = $track['image'][3]['#text'] ? $track['image'][3]['#text'] : 'images/no_artwork.png';

		// nowplaying info
		$track['nowplaying'] = isset($track['@attr']['nowplaying']);
		if(isset($track['@attr'])) unset($track['@attr']); // cleanup

		if($track['mbid']) {
			// load the information from the track api call using mbid
			$track_json = $this->retrieveData($this->api_root . "?format=json&method=track.getInfo&username=" . $this->username . "&api_key=" . $this->api_key . "&mbid=" . urlencode($track['mbid']) /*. "&autocorrect=1"*/);
		} else {
			// if no mbid, try the album+artist
			$track_json = $this->retrieveData($this->api_root . "?format=json&method=track.getInfo&username=" . $this->username . "&api_key=" . $this->api_key . "&artist=" . urlencode($track['artist']) . "&track=" . urlencode($track['name']) . "&autocorrect=1");
		}
		$track_arr = json_decode($track_json, true);

		if((empty($track_arr) || isset($track_arr["error"])) && $track['mbid']) {
			$track_json = $this->retrieveData($this->api_root . "?format=json&method=track.getInfo&username=" . $this->username . "&api_key=" . $this->api_key . "&artist=" . urlencode($track['artist']) . "&track=" . urlencode($track['name']) . "&autocorrect=1");
			$track_arr = json_decode($track_json, true);
		}

		if(empty($track_arr)) {
			throw new exception("Track info empty. API down? Check Last.fm API status.");
		}

		if(isset($track_arr['track'])) {
			$track += $track_arr['track'];
		}

		$track['playcount'] = isset($track['userplaycount']) ? intval($track['userplaycount']) : 'N/A';
		$track['duration'] = isset($track['duration']) ? gmdate("i:s", ($track['duration'] / 1000)) : 'N/A';
		$track['userloved'] = isset($track['userloved']) ? (($track['userloved'] == "1") ? true : false) : false;
		$toolongarr = array('artist', 'name', 'album');

		foreach($track as $key => $value) {
			if(in_array($key, $toolongarr)) {
				// do we know the values ? if not unknown
				$track[$key] = (strlen($value) == 0) ? "Unknown $key" : $value;
			}
		}

		//formatting date

		if (!$track['nowplaying']) {

			$dateFrom = date_create_from_format('j M Y, H:i', $track['date']);

			$dateNow = new DateTime('now');

			$track['date'] = $this->formatDateDiff($dateFrom,$dateNow);

		}	

		$track['username'] = $this->username;

		// cleanup
		unset($track['id']);
		unset($track['listeners']);
		unset($track['toptags']);
		unset($track['userplaycount']);
		return $track;
	}

}
