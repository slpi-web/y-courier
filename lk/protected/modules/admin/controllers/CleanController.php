<?php

class CleanController extends BaseAdminController
{
    public $defaultAction = 'index';

    public function actionIndex($cache = false, $assets = false, $export = false)
    {
        $log = '';
        $status = false;

        if ($cache) {
            Yii::app()->cache->flush();
            $status = true;
        } elseif ($assets) {
            @exec('rm -rfv '.Yii::getPathOfAlias('webroot').DIRECTORY_SEPARATOR.'assets'.DIRECTORY_SEPARATOR.'*', $log);
            $status = true;
        } elseif ($export) {
            @exec('rm -rfv '.Yii::getPathOfAlias('application.runtime.report').DIRECTORY_SEPARATOR.'*', $log);
            $status = true;
        }

        $this->render('index', array(
            'log' => $log,
            'status' => $status,
        ));
    }

}