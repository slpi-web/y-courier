<?php

return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'Офисная Почтовая Служба',
    'sourceLanguage'=>'tok',
    'language'=>'ru',

    'timeZone' => 'Europe/Moscow',

    'defaultController' => 'index',

	'preload'=>array('log', 'booster'),

	'import'=>array(
        'application.models.base.*',
        'application.models.forms.*',
        'application.models.*',
        'application.components.*',
        'ext.YiiMailer.*',
        'ext.easyimage.EasyImage',
	),

	'modules'=>array(
        'admin' => array(
            'modules' => array(
                'webShell'
            )
        ),
        'worker' => array(
        ),
        'client' => array(
        ),
	),

	'components'=>array(

        'clientScript' => array(
            'class' => 'application.extensions.EClientScript.EClientScript',
            'combineScriptFiles' => !YII_DEBUG,
            'combineCssFiles' => !YII_DEBUG,
            'optimizeScriptFiles' => !YII_DEBUG,
            'optimizeCssFiles' => !YII_DEBUG,
            'optimizeInlineScript' => false,
            'optimizeInlineCss' => false,
        ),

        'assetManager' => array(
            'class' => 'application.extensions.EAssetManager.EAssetManager',
            'lessCompile' => true,
            'lessCompiledPath' => 'webroot.assets',
            'lessFormatter' => 'compressed',
            'lessForceCompile' => false,
        ),

        'session' => array(
            'cookieMode' => 'allow',
            'sessionName' => 'EduId',
        ),

        'user' => array(
            'class' => 'application.components.WebUser',
            'allowAutoLogin'=>true,
            'loginUrl' => array('/user/login'),
        ),

        'cseApi' => array(
            'class' => 'application.components.CseApi',
        ),

        'zip' => array(
            'class' => 'application.components.EZip',
        ),

		'urlManager'=>array(
			'urlFormat'=>'path',
            'showScriptName'=>false,
			'rules' => require(dirname(__FILE__).'/routes.php'),
		),

		'db'=>file_exists(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'database_local.php') ? require(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'database_local.php') : require(dirname(__FILE__).'/database.php'),

        'booster' => array(
            'class' => 'application.extensions.YiiBooster.components.Booster',
        ),

        'widgetFactory'=>array(
            'widgets'=>array(
                'CLinkPager'=>array(
                    'cssFile'=>false,
                    'header' => false,
                    'maxButtonCount' => 0,
                    'firstPageLabel' => false,
                    'lastPageLabel' => false,
                    'nextPageLabel' => '<span class="glyphicon glyphicon-arrow-right"></span>',
                    'prevPageLabel' => '<span class="glyphicon glyphicon-arrow-left"></span>',
                ),
                'CListView'=>array(
                    'cssFile'=>false,
                ),
                'CTabView' => array(
                    'cssFile' => false,
                ),
                'WTabView' => array(
                    'cssFile' => false,
                ),
                'CGridView' => array(
                    'cssFile' => false,
                ),
            ),
        ),

		'errorHandler'=>array(
			'errorAction'=>'/system/error',
		),

        'cache' => array(
            'class' => 'CFileCache',
        ),

		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning',
				),
                array(
                    'class' => 'CFileLogRoute',
                    'levels'=>'trace, info',
                    'categories'=>'components.cseApi',
                    'logFile' => 'cseApi.log',
                ),
			),
		),

	),

	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>require(dirname(__FILE__).'/params.php'),
);
