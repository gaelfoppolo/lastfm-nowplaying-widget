<?php

// nowplaying class

class lastfm_nowplaying {

	private $api_root = "http://ws.audioscrobbler.com/2.0/";
	private $user_agent = 'nowplaying widget';
	
	public function __construct($api_key, $size = "small") {
		$this->api_key = $api_key;
		$this->size = $size;

		if(!$this->api_key) { 
			throw new exception("Please set an API key."); 
		}
	}

	private function is_too_long($string) {
		
		if($this->size == "small") $len = 30;
		if($this->size == "big") $len = 25;
		
		// marquees if the string is too long
		if(strlen($string) >= $len) {
			return '<marquee direction="left" behavior="scroll" scroll="on" scrollamount="3">' . $string . '</marquee>';
		} else {
			return $string;
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

	public function info($username) {

		$recent_tracks = $this->retrieveData($this->api_root . "?format=json&method=user.getrecenttracks&user=" . $username . "&api_key=" . $this->api_key . "&limit=5");

		$recent_tracks = json_decode($recent_tracks, true);

		if(isset($recent_tracks["error"]) && ($recent_tracks["error"] == 10)) {
			throw new exception("Unable to get data. Is your API key correct?");
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
			$track_json = $this->retrieveData($this->api_root . "?format=json&method=track.getInfo&username=" . $username . "&api_key=" . $this->api_key . "&mbid=" . urlencode($track['mbid']) /*. "&autocorrect=1"*/);
		} else {
			// if no mbid, try the album+artist
			$track_json = $this->retrieveData($this->api_root . "?format=json&method=track.getInfo&username=" . $username . "&api_key=" . $this->api_key . "&artist=" . urlencode($track['artist']) . "&track=" . urlencode($track['name']) . "&autocorrect=1");
		}
		$track_arr = json_decode($track_json, true);
		$track = $track + $track_arr['track'];
		$track['playcount'] = isset($track['userplaycount']) ? ($track['userplaycount']) : "1";
		$track['playcount'] .= ($track['playcount']=="1") ? " écoute" : " écoutes";
		$track['duration'] = '<strong>&#9835;</strong> '.($track['duration'] ? gmdate("i:s", ($track['duration'] / 1000)) : 'N/A');
		$track['userloved'] = $track['userloved'] ? '<strong>&#x2764;</strong>' : null;
		$toolongarr = array('artist', 'name', 'album');

		foreach($track as $key => $value) {
			if(in_array($key, $toolongarr)) {
				// do we know the values?
				if(strlen($value) == 0) {
					$track[$key] = "Unknown $key";
				} else { // if its not there, we don't need to check if it's too long...
					$track[$key] = $this->is_too_long($value);
				}
			}
		}

		//formatting date

		if (isset($track['nowplaying'])) {

			$dateFrom = date_create_from_format('j M Y, H:i', $track['date']);

			$dateNow = new DateTime('now');

			$track['date'] = $this->formatDateDiff($dateFrom,$dateNow);

		}	

		// cleanup
		unset($track['id']);
		unset($track['listeners']);
		unset($track['toptags']);
		unset($track['userplaycount']);
		return $track;
	}

}
