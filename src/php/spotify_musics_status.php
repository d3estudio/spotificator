<?php
session_start();
if(isset($_SESSION['musics']) AND is_array($_SESSION['musics']))
    echo json_encode(array('response'=>'success', 'musics'=>$_SESSION['musics']));
else
    echo json_encode(array('response'=>'error'));
?>