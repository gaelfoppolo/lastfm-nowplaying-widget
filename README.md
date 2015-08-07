![Last.fm](https://upload.wikimedia.org/wikipedia/commons/thumb/d/d4/Lastfm_logo.svg/709px-Lastfm_logo.svg.png)
Now Playing Widget
===================
A simple widget that displays a user's currently played song on **[Last.fm](http://www.last.fm/)**.

Results
-------------

![](http://i.imgur.com/rzNfIVwm.png)![](http://i.imgur.com/NMMwkJtm.png)
![](http://i.imgur.com/90wSwbfm.png)![](http://i.imgur.com/ScYThptm.png)

Demo
-------------

A live demo is available [here](http://www.gaelfoppolo.com/projets/lastfm/demo.html).

Usage
-------------

### Last.fm API key ###

To use this widget, you'll need an Last.fm API key.

 1. Browse [Last.fm API website](http://www.last.fm/api) and click on
    **Get an API account**.
 2. Log in with your Last.fm account.
 3. Then fill the form of you API key request and click on **Create account**.
 
Your API key is now generated.

Open `nowplaying.php` and fill `$api_key` and `$username` with your data.
Example:
``` php 
$api_key = "c3a41cdadfe269b2082b12eac19ec77f";
$username = "iDrago";
```
Then on your web server (Apache, nginx, etc.), browse `nowplaying.php`, you should see something like that:

``` javascript
{
    "artist": "Iron Maiden",
    "name": "Fear of the Dark (live, Rock in Rio)",
    "streamable": "0",
    "mbid": "42c4a942-d0bc-4ce1-b184-1935d3372a90",
    "album": "Unknown album",
    "url": "http:\/\/www.last.fm\/music\/Iron+Maiden\/_\/Fear+of+the+Dark+(live,+Rock+in+Rio)",
    "image": "images\/no_artwork.png",
    "nowplaying": true,
    "duration": "08:04",
    "playcount": 1,
    "userloved": false,
    "username": "iDrago"
}
```
If not, your username or your API key is not faulty.
 
### Options ###

Open `js/nowplaying.js` and fill the following variables as you want.
Here an example:
``` php 
var color = "red"; 			 // available: red, black
var size = "big"; 			 // available: big, small
var refreshInterval = 60000; // in milliseconds
```
Recommended value for `refreshInterval` starts to **30000** (30 seconds).

**Alternatively, fork this repo and customise it to your liking! **