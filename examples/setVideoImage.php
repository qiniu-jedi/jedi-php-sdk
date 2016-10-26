<?php
include_once 'config.php';

use Qiniu\Jedi;

$videoKey = 'qiniu.mp4';
$activeIndex = 9;

$jediAuth = new Jedi\JediAuth($ak, $sk);
$jediManager = new Jedi\JediManager($jediAuth);
$result = $jediManager->setVideoImage($hub, $videoKey, $activeIndex);

print_r($result['result']);
print_r($result['response']);
