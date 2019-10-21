<?php

class AuthController extends BaseAdminController
{
    public $defaultAction = 'login';

    public function accessRules()
    {
        return array(
            array('allow',
                'users'=>array('*'),
            ),

            array('deny',
                'users'=>array('*'),
            ),
        );
    }

    public function actions() {
        return array(
            'captcha'=>array(
                'class'=>'CCaptchaAction',
                'backColor' => 0xF5F5F5,
            ),
        );
    }

    public function actionLogin()
    {
        if (!Yii::app()->user->isGuest) {
            if (!Yii::app()->user->isAdmin())
                Yii::app()->user->logout();
            $this->redirect(array('/admin/index'));
        }

        $model = new UserLoginForm;

        $model->allowAdminOnly();

        if(isset($_POST[get_class($model)]))
        {
            $model->attributes=$_POST[get_class($model)];

            if ($model->validate()) {
                /*if (Yii::app()->user->returnUrl)
                    if (strpos(Yii::app()->user->returnUrl, 'admin') > 0)
                        $this->redirect(Yii::app()->user->returnUrl);*/

                $this->redirect(array('/admin/index'));
            }
        }

        $this->render('login', array('model'=>$model));
    }

    public function actionLogout()
    {
        if (!Yii::app()->user->isGuest) {
            Yii::app()->user->logout();
        }
        Yii::app()->user->logout();
        $this->redirect(Yii::app()->homeUrl);
    }

}