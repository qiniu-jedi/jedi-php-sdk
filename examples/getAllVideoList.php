<?php

include_once 'config.php';

use Jedi;

$jediAuth = new Jedi\JediAuth($ak, $sk);
$jediManager = new Jedi\JediManager($jediAuth);


$count = 3;

$result = $jediManager->getVideoList($hub, $count);
if (!empty($result)) {
    $cursor = $result['videoList']['cursor'];
    $totalCount = $result['videoList']['count'];
    $videoItems = $result['videoList']['items'];
    foreach ($videoItems as $videoItem) {
        //output video info
        print($videoItem['key'] . "\t" . $videoItem['name'] . "\n");
    }

    while (!empty($cursor)) {
        $result = $jediManager->getVideoList($hub, $count, $cursor);
        if (!empty($result)) {
            $cursor = $result['videoList']['cursor'];
            $totalCount = $result['videoList']['count'];
            $videoItems = $result['videoList']['items'];
            foreach ($videoItems as $videoItem) {
                //output video info
                print($videoItem['key'] . "\t" . $videoItem['name'] . "\n");
            }
        } else {
            print_r($result['response']);
            break;
        }
    }
} else {
    print_r($result['response']);
}
