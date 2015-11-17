<?php

define('DEBUG', true);
if (DEBUG) {
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
}

define('RDIO_CONSUMER_KEY', 'si23k3mibzfupnozxogyquvc5a');
define('RDIO_CONSUMER_SECRET', 'SKW1ZRQkWRRZrjwGL4aong');

define('SPOTIFY_CONSUMER_KEY', 'a8c190a5aca14d25b16d064e434b3e00');
define('SPOTIFY_CONSUMER_SECRET', '5ebddfff15d34831aff4ea50004c2ccf');

if (DEBUG) {
    define('SPOTIFY_REDIRECT_URL', 'http://localhost/spotificator/src/php/spotify_connect.php');
}else{
    define('SPOTIFY_REDIRECT_URL', 'http://www.spotificator.com.br/spotify_connect.php');
}

if (!DEBUG) {
    define('DEEZER_APP_ID', '137843');
    define('DEEZER_APP_SECRET', '4f723a204b9a38fe7e5a5094b72110c7');

    define('PLAYLISTS_COUNT_LIMIT', 60);
    define('MUSICS_COUNT_LIMIT', 50);
} else {
    define('DEEZER_APP_ID', '138063');
    define('DEEZER_APP_SECRET', '1f29c4f10238ce34a874802d9410980f');

    define('PLAYLISTS_COUNT_LIMIT', 60);
    define('MUSICS_COUNT_LIMIT', 50);
}

date_default_timezone_set('Brazil/East');
ini_set('allow_url_fopen', 1);
set_time_limit(600);
?>
