<?php
session_start();

include_once('thirdparty/spotify/Request.php');
include_once('thirdparty/spotify/Session.php');
include_once('thirdparty/spotify/SpotifyWebAPI.php');
include_once('thirdparty/spotify/SpotifyWebAPIException.php');

$_SESSION['musics'] = array();
$_SESSION['musics']['found'] = array();
$_SESSION['musics']['not_found'] = array();
session_write_close();

require_once 'config.php';
require_once 'thirdparty/spotify.class.php';


$request = isset($_POST['playlist']) ? $_POST : $_GET;

if (!isset($request['playlist']) || empty($request['playlist'])) {
    echo json_encode(array('response'=>'error', 'message_error'=>'empty_list'));
    exit();
}
else {
    $playlist_name = $request['playlist']['name'];
    $playlist_musics = $request['playlist']['musics'];
}

$session = new SpotifyWebAPI\Session(
    SPOTIFY_CONSUMER_KEY,
    SPOTIFY_CONSUMER_SECRET,
    SPOTIFY_REDIRECT_URL
);

$api = new SpotifyWebAPI\SpotifyWebAPI();
$api->setAccessToken($_SESSION['spotify_token']);

$spotify_playlist = $api->createUserPlaylist($_SESSION['spotify_user_id'], array(
    'name' => $playlist_name
));

$response['response'] = 'success';
$response['link'] = false;

$music_ids = array();
foreach ($playlist_musics as $music)
{
    try {
        $spotifyTrack = Spotify::searchTrackGetFirstID($music);
    }
    catch(Exception $e) {
        $spotifyTrack = false;
    }

    session_start();
    if($spotifyTrack) {
        $music_ids[] = $spotifyTrack;
        $_SESSION['musics']['found'][] = $music;
    }
    else {
        $_SESSION['musics']['not_found'][] = $music;
    }
    session_write_close();
}

if(sizeof($music_ids) > 0) {
    $api->addUserPlaylistTracks($_SESSION['spotify_user_id'], $spotify_playlist->id, $music_ids);
    $response['link'] = $spotify_playlist->external_urls->spotify;
    //$response['link'] = 'http://open.spotify.com/trackset/' . urlencode($playlist_name) . '/' . implode(',', $music_ids);
}
echo json_encode($response);
