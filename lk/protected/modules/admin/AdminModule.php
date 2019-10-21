<?php

class AdminModule extends CWebModule
{
    public $defaultController = 'index';

    public function init()
    {
        $this->setImport(array(
            'admin.models.*',
            'admin.components.*',
            'application.components.report.*'
        ));
    }

    public function beforeControllerAction($controller, $action)
    {
        if (parent::beforeControllerAction($controller, $action)) {
            Yii::app()->user->loginUrl = array('/admin/auth/login');
            return true;
        }

        return false;
    }

}