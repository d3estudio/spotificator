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


if (isset($_POST['playlist']['id'])) {
  $playlist_id = $_POST['playlist']['id'];
  $playlist_name = $_POST['playlist']['name'];
}
else {
  echo json_encode(array('response'=>'error', 'message_error'=>'playlist_id_undefined'));
  exit();
}


if (isset($_POST['page']) AND $_POST['page'] > 0) $page = $_POST['page'];
else $page = 160;
$start = ($page - 1) * MUSICS_COUNT_LIMIT;


$playlist = false;
$musics = array();
$pagination = false;
try {
  if ($playlist_id AND $playlist_id != '0') {
    $playlists = $rdio->call('get', array('keys' => $playlist_id, 'extras' => 'tracks'));
    if ($playlists AND isset($playlists->result) AND isset($playlists->result->{$playlist_id}) AND isset($playlists->result->{$playlist_id}->tracks) AND sizeof($playlists->result->{$playlist_id}->tracks) > 0) {
      $playlist = $playlists->result->{$playlist_id};
      for ($i = $start; $i < sizeof($playlist->tracks); $i++)
      {
        if (isset($playlist->tracks[$i]->name) AND !empty($playlist->tracks[$i]->name)) {
          $musics[] = $playlist->tracks[$i]->name;
          if (sizeof($musics) >= MUSICS_COUNT_LIMIT) break;
        }
      }
      $pagination = ($start + MUSICS_COUNT_LIMIT) < sizeof($playlist->tracks) ? true : false;
    }
    else {
      $playlists = false;
    }
  }
  else {
    $collection = $rdio->call('getTracksInCollection', array('start' => $start, 'count' => MUSICS_COUNT_LIMIT));
    if ($collection AND isset($collection->status) AND $collection->status == 'ok' AND isset($collection->result)) {
      $playlist = true;
      foreach ($collection->result as $music) {
        if (isset($music->name) AND !empty($music->name))
          $musics[] = $music->name;
      }
      if (sizeof($musics) > 0)
        $pagination = sizeof($collection->result) >= MUSICS_COUNT_LIMIT ? true : false;
      else
        $pagination = false;
    }
    else {
      $playlists = false;
    }
  }
} catch (Exception $e) {}
if(!$playlist) {
  echo json_encode(array('response'=>'error', 'message_error'=>'playlist_not_found'));
  exit();
}


$response['response'] = 'success';
$response['playlist']['id'] = $playlist_id;
$response['playlist']['name'] = $playlist_name;
$response['playlist']['picture'] = isset($playlist->icon) ? $playlist->icon : false;
$response['playlist']['musics'] = $musics;
$response['pagination'] = $pagination;
echo json_encode($response);
?>