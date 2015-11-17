<?php
session_start();

require_once 'config.php';
require_once 'thirdparty/rdio.class.php';

function signed_post($url) {
    $authentication = base64_encode(RDIO_CONSUMER_KEY . ":" . RDIO_CONSUMER_SECRET);

    $curl = curl_init($url);
    // $postbody = http_build_query($params);
    curl_setopt($curl, CURLOPT_VERBOSE, TRUE);
    curl_setopt($curl, CURLOPT_POST, TRUE);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
    // curl_setopt($curl, CURLOPT_POSTFIELDS, $postbody);
    curl_setopt($curl, CURLOPT_HTTPHEADER,
      array('Authorization: Basic ' . $authentication)
    );
    $body = curl_exec($curl);
    curl_close($curl);
    return $body;
}

# create an instance of the Rdio object with our consumer credentials
$rdio = new Rdio(array(RDIO_CONSUMER_KEY, RDIO_CONSUMER_SECRET));

# work out what our current URL is
$current_url = "http" . ((!empty($_SERVER['HTTPS'])) ? 's' : '') . '://' . $_SERVER['SERVER_NAME'] . $_SERVER['SCRIPT_NAME'];

if ($_GET['code']) {
    # we've been passed a verifier, that means that we're in the middle of
    # authentication.
    error_log("-----> authorization_code");
    $url = "https://services.rdio.com/oauth2/token?grant_type=authorization_code&code=". $_GET['code'] . "&redirect_uri=" . urlencode($current_url);
    $result = json_decode(signed_post($url));

    $_SESSION['oauth_token'] = $result->access_token;
  # make sure that we can in fact make an authenticated call
  // $currentUser = $rdio->call('currentUser');
} else {
  # we have no authentication tokens.
  # ask the user to approve this app
  $authorize_url = "https://services.rdio.com/oauth2/authorize?response_type=code&client_id=" . RDIO_CONSUMER_KEY . "&redirect_uri=" . urlencode($current_url);
  error_log($authorize_url);
  header('Location: '.$authorize_url);
}
?>

<script language="javascript">

  <? if($_SESSION['oauth_token']) { ?>
    window.opener.connectPopupSuccess(
    JSON.stringify({
      service: 'rdio',
      paramers:  { oauth_token: '<?=$_SESSION['oauth_token']?>' }
    }));
  <? } ?>
  self.close();

</script>
