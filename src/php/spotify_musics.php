<?php
session_start();
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
    $response['link'] = 'http://open.spotify.com/trackset/' . urlencode($playlist_name) . '/' . implode(',', $music_ids);
}
echo json_encode($response);
