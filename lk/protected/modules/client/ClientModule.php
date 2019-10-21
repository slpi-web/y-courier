<?php

class ClientModule extends CWebModule
{
    public $defaultController = 'index';

    public function init()
    {
        $this->setImport(array(
            'client.models.*',
            'client.components.*',
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