<?php


require_once './vendor/autoload.php';
use Demo\Phptools\Tools;

$res = Tools::get('www.baidu.com');
var_dump($res);