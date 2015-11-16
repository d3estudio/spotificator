<?php
class Deezer {

    private $app_id;
    private $app_secret;
    private $access_token;

    function __construct($app_id, $app_secret, $access_token = false) {
        $this->app_id = $app_id;
        $this->app_secret = $app_secret;

        $this->setAccessToken($access_token);
    }

    public function authenticate($callback_url) {
        if(empty($_REQUEST['code'])) {
            $_SESSION['state'] = md5(uniqid(rand(), TRUE)); //CSRF protection
            $dialog_url = 'https://connect.deezer.com/oauth/auth.php?app_id='.$this->app_id.'&redirect_uri='.urlencode($callback_url).'&perms=email,offline_access'.'&state='. $_SESSION['state'];
            header('Location: '.$dialog_url);
            exit;
        }
        if($_REQUEST['state'] == $_SESSION['state']) {
            $token_url = 'https://connect.deezer.com/oauth/access_token.php?app_id='.$this->app_id.'&secret='.$this->app_secret.'&code='.$_REQUEST['code'];
            $response  = file_get_contents($token_url);
            $params = null;
            parse_str($response, $params);
            if(isset($params['access_token']) AND !empty($params['access_token'])) {
                $this->setAccessToken($params['access_token']);
                return true;
            }
            else {
                return false;
            }
        } else {
            //The state does not match. You may be a victim of CSRF
            return false;
        }
    }

    public function setAccessToken($access_token) {
        if(!$access_token)
            $access_token = $this->getAccessToken();

        $this->access_token = $access_token;
        $_SESSION['access_token'] = $access_token;
    }

    public function getAccessToken() {
        if(isset($_SESSION['access_token']) AND !empty($_SESSION['access_token']))
            return $_SESSION['access_token'];
        else if($this->access_token)
            return $this->access_token;
        else
            return false;
    }

    public function getUser() {
        $api_url = 'https://api.deezer.com/user/me?access_token='.$this->access_token;
        $user = json_decode(file_get_contents($api_url));
        return $user;
    }

    public function getPlaylistData($playlist_id) {
        $playlistURL = 'http://api.deezer.com/playlist/'.$playlist_id;
        $playlist = json_decode(file_get_contents($playlistURL));
        return $playlist;
    }

    public function getPlaylists($user = false, $count=100) {
        if(!$user)
            $user = $this->getUser();

        $playlistURL = 'http://api.deezer.com/user/'.$user->id.'/playlists?index=0&limit='.$count;
        $playlists = json_decode(file_get_contents($playlistURL));

        if(!isset($playlists->error) AND isset($playlists->data) AND isset($playlists->total) AND $playlists->total >= 1) {
            return $playlists->data;
        }
        else {
            return false;
        }
    }
}