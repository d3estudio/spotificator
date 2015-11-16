<?php
require_once 'config.php';
require_once 'thirdparty/deezer.class.php';

session_start();
$deezer = new Deezer(DEEZER_APP_ID, DEEZER_APP_SECRET);
$access_token = $deezer->getAccessToken();

if(isset($_POST['access_token']) AND !empty($_POST['access_token'])) {
    $deezer->setAccessToken($_POST['access_token']);
}
session_write_close();
if(!$access_token) {
    echo json_encode(array('response'=>'error', 'message_error'=>'token_undefined'));
    exit();
}

$myPlaylists = $deezer->getPlaylists(false, PLAYLISTS_COUNT_LIMIT);
if(!$myPlaylists) {
  echo json_encode(array('response'=>'error', 'message_error'=>'playlist_not_found'));
  exit();
}

$response['response'] = 'success';
$response['playlists'] = array();
$i = 0;
foreach($myPlaylists as $playlist) {
    if($playlist->type == 'playlist' AND isset($playlist->title) AND $playlist->title) {
        $response['playlists'][$i]['id'] = $playlist->id;
        $response['playlists'][$i]['type'] = 'deezer';
        $response['playlists'][$i]['name'] = $playlist->title;
        $i++;
    }
}
echo json_encode($response);
?>