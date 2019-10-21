<?php

class IndexController extends BaseController
{

    public $defaultAction = 'index';

    public function actionIndex()
    {
        if (Yii::app()->user->isGuest)
            $this->redirect(array('/user/login'));
        else {
            $user = Yii::app()->user->loadUser();
            if ($user) {
                if ($user->isAdmin())
                    $this->redirect(array('/admin/index/index'));
                elseif ($user->isWorker())
                    $this->redirect(array('/worker/index/index'));
                else
                    $this->redirect(array('/client/index/index'));
            }
        }
    }

}