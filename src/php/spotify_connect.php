<?php
error_reporting(-1);
ini_set('display_errors', 1);
session_start();
include_once('thirdparty/spotify/Request.php');
include_once('thirdparty/spotify/Session.php');
include_once('thirdparty/spotify/SpotifyWebAPI.php');
include_once('thirdparty/spotify/SpotifyWebAPIException.php');
include_once('config.php');
$session = new SpotifyWebAPI\Session(
    SPOTIFY_CONSUMER_KEY,
    SPOTIFY_CONSUMER_SECRET,
    SPOTIFY_REDIRECT_URL
);

$api = new SpotifyWebAPI\SpotifyWebAPI();

if (isset($_GET['code'])) {
    $session->requestAccessToken($_GET['code']);
    $api->setAccessToken($session->getAccessToken());
    $_SESSION['spotify_token'] = $session->getAccessToken();
    $_SESSION['spotify_user_id'] = $api->me()->id;

    // $playlist = $api->createUserPlaylist($_SESSION['spotify_user_id'], array(
    //     'name' => 'joao teste'
    // ));

    // echo '<pre>';
    // print_r($playlist);
    // echo '</pre>';
    // exit;
    ?><script>window.close();</script><?
} else {
    $scopes = array(
        'scope' => array(
            'playlist-modify',
        ),
    );
    header('Location: ' . $session->getAuthorizeUrl($scopes));
}
