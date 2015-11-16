<?php

define('DEBUG', false);
if (DEBUG) {
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
}

define('RDIO_CONSUMER_KEY', '735nnd2m5pu7efwvyr2smxn6');
define('RDIO_CONSUMER_SECRET', 'Sk5n4EAHUs');

if (!DEBUG) {
    define('DEEZER_APP_ID', '137843');
    define('DEEZER_APP_SECRET', '4f723a204b9a38fe7e5a5094b72110c7');

    define('PLAYLISTS_COUNT_LIMIT', 60);
    define('MUSICS_COUNT_LIMIT', 50);
} else {
    define('DEEZER_APP_ID', '138063');
    define('DEEZER_APP_SECRET', '1f29c4f10238ce34a874802d9410980f');

    define('PLAYLISTS_COUNT_LIMIT', 60);
    define('MUSICS_COUNT_LIMIT', 2);
}

date_default_timezone_set('Brazil/East');
ini_set('allow_url_fopen', 1);
set_time_limit(600);
?>