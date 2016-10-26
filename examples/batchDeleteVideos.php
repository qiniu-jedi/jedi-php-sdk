<?php

include_once 'config.php';

$videoKeys = array('qiniu.mp4', 'qiniu2.mp4', 'qiniu3.mp4');

$jediAuth = new Jedi\JediAuth($ak, $sk);
$jediManager = new Jedi\JediManager($jediAuth);
$result = $jediManager->batchdeleteVideos($hub, $videoKeys);

print_r($result['result']);
print_r($result['response']);
