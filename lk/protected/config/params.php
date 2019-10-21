<?php
$config =  array(
    'enableCaptcha' => false,
    'autocompletePageSize' => 10,
);

$file = dirname(__FILE__).'/user_params.inc';
$content = file_get_contents($file);
$arr = unserialize(base64_decode($content));
return CMap::mergeArray(
    $config,
    $arr
);