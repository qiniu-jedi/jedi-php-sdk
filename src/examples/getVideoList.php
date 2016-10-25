<?php

include_once '../Jedi/JediAuth.php';
include_once '../Jedi/JediManager.php';
include_once '../../vendor/qiniu/php-sdk/autoload.php';
include_once 'config.php';

use Qiniu\Jedi;

$jediAuth = new Jedi\JediAuth($ak, $sk);
$jediManager = new Jedi\JediManager($jediAuth);
$result = $jediManager->getVideoList($hub);


print_r($result['videoList']);
$cursor = $result['videoList']['cursor'];
if (empty($cursor)) {
    print("cursor is null");
} else {
    print("cursor is " . $cursor);
}
print_r($result['videoList']['total']);
#print_r($result['response']);
