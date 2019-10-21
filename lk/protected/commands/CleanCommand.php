<?php

class CleanCommand extends CConsoleCommand
{

    public function actionCache()
    {
        Yii::app()->cache->flush();
        echo "\n".'Cache successfully removed.'."\n\n";
    }

    public function actionAssets()
    {
        @exec('rm -rfv '.Yii::getPathOfAlias('webroot').DIRECTORY_SEPARATOR.'assets'.DIRECTORY_SEPARATOR.'*', $log);
        echo "\n".'Assets successfully removed.'."\n\n";
    }

    public function actionReports()
    {
        @exec('rm -rfv '.Yii::getPathOfAlias('application.runtime.report').DIRECTORY_SEPARATOR.'*', $log);
        echo "\n".'Reports cache successfully removed.'."\n\n";
    }

}