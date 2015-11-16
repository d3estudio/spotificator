<?php
session_start();

require_once 'config.php';
require_once 'thirdparty/deezer.class.php';

$current_url = 'http' . ((!empty($_SERVER['HTTPS'])) ? 's' : '') . '://' . $_SERVER['SERVER_NAME'] . $_SERVER['SCRIPT_NAME'];

$deezer = new Deezer(DEEZER_APP_ID, DEEZER_APP_SECRET);

$deezer->authenticate($current_url);

$access_token = $deezer->getAccessToken();
?>

<script language="javascript">
  window.opener.connectPopupSuccess(
    JSON.stringify({
      service: 'deezer',
      paramers:  { access_token: '<?=$access_token?>' }
    }));
  self.close();

</script>
