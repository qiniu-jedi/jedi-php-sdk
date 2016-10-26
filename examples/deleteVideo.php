<?php

include_once 'config.php';

$videoKey = 'qiniu.mp4';

$jediAuth = new Jedi\JediAuth($ak, $sk);
$jediManager = new Jedi\JediManager($jediAuth);
$result = $jediManager->deleteVideo($hub, $videoKey);

print_r($result['result']);
print_r($result['response']);
