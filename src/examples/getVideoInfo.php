<?php

include_once '../Jedi/JediAuth.php';
include_once '../Jedi/JediManager.php';
include_once '../../vendor/qiniu/php-sdk/autoload.php';
include_once 'config.php';

use Qiniu\Jedi;

$videoKey = 'qiniu.mp4';

$jediAuth = new Jedi\JediAuth($ak, $sk);
$jediManager = new Jedi\JediManager($jediAuth);
$result = $jediManager->getVideoInfo($hub, $videoKey);

print_r($result['videoInfo']);
#print_r($result['videoInfo']['key']);
print_r($result['response']);
