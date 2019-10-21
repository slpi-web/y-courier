<?php

/**
 * Created by PhpStorm.
 * User: dim
 * Date: 03.12.2015
 * Time: 10:33
 */
class DebtController extends BaseClientController
{

    public $defailtAction ='limit';

    protected function beforeAction($action)
    {
        return true;
    }

    public function actionLimit()
    {
        $user = Yii::app()->user->loadUser();
        if ($user) {
            if ($user->debt_limit <= 0)
                $this->redirect('/client/index/index');
            if ($user->debt < $user->debt_limit)
                $this->redirect('/client/index/index');

            $this->render('limit');
            Yii::app()->end();
        }
        $this->redirect('/client/index/index');
    }

}