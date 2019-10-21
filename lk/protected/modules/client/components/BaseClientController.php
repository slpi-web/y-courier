<?php

class BaseClientController extends BaseController
{

    public $layout = '/layouts/main';

    public $headerButton = '';

    public function filters() {
        return array(
            'accessControl',
            array('application.extensions.YiiBooster.filters.BoosterFilter - delete'),
        );
    }

    public function accessRules()
    {
        return array(
            array('allow',
                'users'=>array('@'),
                'expression' => '$user->isClient()',
            ),

            array('deny',
                'users'=>array('*'),
            ),
        );
    }

    protected function beforeAction($action)
    {
        if (parent::beforeAction($action)) {
            $user = Yii::app()->user->loadUser();
            if ($user) {
                if (($user->debt_limit <= 0))
                    return true;
                if ($user->debt < $user->debt_limit)
                    return true;
                $this->redirect('/client/debt/limit');
            }
            return false;
        } else
            return false;
    }

}