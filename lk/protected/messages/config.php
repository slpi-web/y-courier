<?php

return array(
    'sourcePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
    'messagePath'=>dirname(__FILE__),
    'languages'=>array('ru'),
    'fileTypes'=>array('php'),
    'translator'=>'Yii::t',
    'overwrite'=>true,
    'exclude'=>array(
        '.svn',
        'yiilite.php',
        'yiit.php',
        '/i18n/data',
        '/messages',
        '/vendors',
        '/web/js',
    ),
);