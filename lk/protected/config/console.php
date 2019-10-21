<?php

return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'Office Post Console',
	'sourceLanguage'=>'tok',
	'language'=>'ru',

	'timeZone' => 'Europe/Moscow',

	'preload'=>array('log'),

	'import'=>array(
		'application.models.base.*',
		'application.models.forms.*',
		'application.models.*',
		'application.components.*',
		'ext.YiiMailer.*',
		'ext.easyimage.EasyImage',
	),

	'components'=>array(

        'db'=>file_exists(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'database_local.php') ? require(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'database_local.php') : require(dirname(__FILE__).'/database.php'),

		'cseApi' => array(
			'class' => 'application.components.CseApi',
		),

		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning',
				),
			),
		),

	),

	'params'=>require(dirname(__FILE__).'/params.php'),
);
