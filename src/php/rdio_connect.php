<?php
session_start();

require_once 'config.php';
require_once 'thirdparty/rdio.class.php';

# create an instance of the Rdio object with our consumer credentials
$rdio = new Rdio(array(RDIO_CONSUMER_KEY, RDIO_CONSUMER_SECRET));

# work out what our current URL is
$current_url = "http" . ((!empty($_SERVER['HTTPS'])) ? 's' : '') . '://' . $_SERVER['SERVER_NAME'] . $_SERVER['SCRIPT_NAME'];

if ($_SESSION['oauth_token'] && $_SESSION['oauth_token_secret']) {
  # we have a token in our session, let's use it
  $rdio->token = array($_SESSION['oauth_token'],$_SESSION['oauth_token_secret']);

  if ($_GET['oauth_verifier']) {
    # we've been passed a verifier, that means that we're in the middle of
    # authentication.
    $rdio->complete_authentication($_GET['oauth_verifier']);
    # save the new token in our session
    $_SESSION['oauth_token'] = $rdio->token[0];
    $_SESSION['oauth_token_secret'] = $rdio->token[1];
  }
  # make sure that we can in fact make an authenticated call
  $currentUser = $rdio->call('currentUser');
  if(!$currentUser)
  {
    # auth failure, clear session
    session_destroy();
    # and start again
    header('Location: '.$current_url);
    header('Content-Type: text/html; charset=utf-8');
  }
} else {
  # we have no authentication tokens.
  # ask the user to approve this app
  $authorize_url = $rdio->begin_authentication($current_url);
  # save the new token in our session
  $_SESSION['oauth_token'] = $rdio->token[0];
  $_SESSION['oauth_token_secret'] = $rdio->token[1];

  header('Location: '.$authorize_url);
}
?>

<script language="javascript">

  <? if(isset($rdio) AND isset($rdio->token[0])) { ?>
    window.opener.connectPopupSuccess(
    JSON.stringify({
      service: 'rdio',
      paramers:  { oauth_token: '<?=$rdio->token[0]?>', oauth_token_secret: '<?=$rdio->token[1]?>' }
    }));
  <? } ?>
  self.close();

</script>
