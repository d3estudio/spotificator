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


if (isset($_POST['playlist']['id'])) {
  $playlist_id = $_POST['playlist']['id'];
  $playlist_name = $_POST['playlist']['name'];
}
else {
  echo json_encode(array('response'=>'error', 'message_error'=>'playlist_id_undefined'));
  exit();
}


if (isset($_POST['page']) AND $_POST['page'] > 0) $page = $_POST['page'];
else $page = 1;
$start = ($page - 1) * MUSICS_COUNT_LIMIT;


$playlist = false;
$musics = array();

try {
  $playlist = $deezer->getPlaylistData($playlist_id);
} catch (Exception $e) { $playlist = false; }
if(!$playlist) {
  echo json_encode(array('response'=>'error', 'message_error'=>'playlist_not_found'));
  exit();
}
else if (!isset($playlist->tracks) || !isset($playlist->tracks->data) || sizeof($playlist->tracks->data) == 0) {
  echo json_encode(array('response'=>'error', 'message_error'=>'playlist_empty'));
  exit();
}


for ($i = $start; $i < sizeof($playlist->tracks->data); $i++)
{
  $music = $playlist->tracks->data[$i];
  if (isset($music->title) AND $music->title) {
    $musics[] = $music->title;
    if (sizeof($musics) >= MUSICS_COUNT_LIMIT) break;
  }
}
$pagination = ($start + MUSICS_COUNT_LIMIT) < sizeof($playlist->tracks->data) ? true : false;


if ($musics AND sizeof($musics) > 0) {
  $response['response'] = 'success';
  $response['playlist']['id'] = $playlist_id;
  $response['playlist']['name'] = $playlist_name;
  $response['playlist']['picture'] = isset($playlist->picture) ? $playlist->picture : false;
  $response['playlist']['musics'] = $musics;
  $response['pagination'] = $pagination;
  echo json_encode($response);
}
else {
  echo json_encode(array('response'=>'error', 'message_error'=>'musics_not_found'));
}
?>