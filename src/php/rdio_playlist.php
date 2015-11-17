<?php
require_once 'config.php';
require_once 'thirdparty/rdio.class.php';


session_start();
if (isset($_POST['oauth_token'])) {
  $oauth_token = $_POST['oauth_token'];
}
else if (isset($_SESSION['oauth_token'])) {
  $oauth_token = $_SESSION['oauth_token'];
}
else {
  echo json_encode(array('response'=>'error', 'message_error'=>'token_undefined'));
  exit();
}
session_write_close();

# create an instance of the Rdio object with our consumer credentials
// $rdio = new Rdio(array(RDIO_CONSUMER_KEY, RDIO_CONSUMER_SECRET), array($oauth_token, $oauth_token_secret));

function post_to_rdio($url, $params) {
  $curl = curl_init($url);
  $postbody = http_build_query($params);
  //curl_setopt($curl, CURLOPT_VERBOSE, TRUE);
  curl_setopt($curl, CURLOPT_POST, TRUE);
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
  curl_setopt($curl, CURLOPT_POSTFIELDS, $postbody);
  curl_setopt($curl, CURLOPT_HTTPHEADER,
    array('Content-type: application/x-www-form-urlencoded'));
  $body = curl_exec($curl);
  curl_close($curl);
  return $body;
}

$myPlaylists = json_decode(post_to_rdio('https://services.rdio.com/api/1/?access_token='.$oauth_token, array('start' => 0, 'count' => PLAYLISTS_COUNT_LIMIT, 'method' => 'getPlaylists')))->result->owned;
$myCollection = json_decode(post_to_rdio('https://services.rdio.com/api/1/?access_token='.$oauth_token, array('start' => 0, 'count' => 1, 'method' => 'getTracksInCollection')));
// $myPlaylists = $rdio->call('getPlaylists', array('start' => 0, 'count' => PLAYLISTS_COUNT_LIMIT))->result->owned;
// $myCollection = $rdio->call('getTracksInCollection', array('start' => 0, 'count' => 1));
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