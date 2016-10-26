<?php
include_once 'config.php';

use Qiniu\Jedi;

$jediAuth = new Jedi\JediAuth($ak, $sk);
$jediManager = new Jedi\JediManager($jediAuth);
$result = $jediManager->getUpToken($hub);
print_r($result["uptoken"]);
print_r($result["response"]);
