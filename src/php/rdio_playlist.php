<?php
require_once 'config.php';
require_once 'thirdparty/rdio.class.php';


session_start();
if (isset($_POST['oauth_token']) AND isset($_POST['oauth_token_secret'])) {
  $oauth_token = $_POST['oauth_token'];
  $oauth_token_secret = $_POST['oauth_token_secret'];
}
else if (isset($_SESSION['oauth_token']) AND isset($_SESSION['oauth_token_secret'])) {
  $oauth_token = $_SESSION['oauth_token'];
  $oauth_token_secret = $_SESSION['oauth_token_secret'];
}
else {
  echo json_encode(array('response'=>'error', 'message_error'=>'token_undefined'));
  exit();
}
session_write_close();

# create an instance of the Rdio object with our consumer credentials
$rdio = new Rdio(array(RDIO_CONSUMER_KEY, RDIO_CONSUMER_SECRET), array($oauth_token, $oauth_token_secret));

$myPlaylists = $rdio->call('getPlaylists', array('start' => 0, 'count' => PLAYLISTS_COUNT_LIMIT))->result->owned;
$myCollection = $rdio->call('getTracksInCollection', array('start' => 0, 'count' => 1));
if ($myCollection AND isset($myCollection->result) AND sizeof($myCollection->result) > 0) {
  $objPlaylist = new stdClass();
  $objPlaylist->key = 0;
  $objPlaylist->name = 'Collection';
  $myPlaylists[] = $objPlaylist;
}
if(!$myPlaylists) {
  echo json_encode(array('response'=>'error', 'message_error'=>'playlist_not_found'));
  exit();
}


$response['response'] = 'success';
$response['playlists'] = array();
$i = 0;
foreach($myPlaylists as $playlist)
{
  if (isset($playlist->name) AND $playlist->name) {
    $response['playlists'][$i]['id'] = $playlist->key;
    $response['playlists'][$i]['type'] = 'rdio';
    $response['playlists'][$i]['name'] = $playlist->name;
    $i++;
  }
}
echo json_encode($response);
?>