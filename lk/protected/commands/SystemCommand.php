<?php

class SystemCommand extends CConsoleCommand
{

    public function actionDiskUsage($path = '*', $verbose = false)
    {
        @exec('du -s'.(!$verbose ? 'h' : '').' '.$path, $log);
        foreach ($log as $logLine)
            echo "\n".$logLine;
        echo "\n\n";
    }

    public function actionGetPath()
    {
        echo Yii::app()->getbasePath();
        echo "\n\n";
    }

}