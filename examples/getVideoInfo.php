<?php
include_once 'config.php';

use Qiniu\Jedi;

$videoKey = 'qiniu.mp4';

$jediAuth = new Jedi\JediAuth($ak, $sk);
$jediManager = new Jedi\JediManager($jediAuth);
$result = $jediManager->getVideoInfo($hub, $videoKey);

print_r($result['videoInfo']);
#print_r($result['videoInfo']['key']);
print_r($result['response']);
