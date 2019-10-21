<?php

class WorkerModule extends CWebModule
{
    public $defaultController = 'index';

    public function init()
    {
        $this->setImport(array(
            'worker.models.*',
            'worker.components.*',
            'application.components.report.*'
        ));
    }

    public function beforeControllerAction($controller, $action)
    {
        if (parent::beforeControllerAction($controller, $action)) {
            Yii::app()->user->loginUrl = array('/user/login');
            return true;
        }

        return false;
    }
}