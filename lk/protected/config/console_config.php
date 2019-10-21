<?php
$configName = 'console';

if (file_exists(dirname(__FILE__).DIRECTORY_SEPARATOR.$configName.'_local.php'))
    $localConfig = require(dirname(__FILE__).DIRECTORY_SEPARATOR.$configName.'_local.php');

$config = require(dirname(__FILE__).DIRECTORY_SEPARATOR.$configName.'.php');

if (isset($localConfig))
    $config = CMap::mergeArray($config, $localConfig);

return $config;