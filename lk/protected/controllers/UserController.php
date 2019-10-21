<?php

class UserController extends BaseController
{

    public $defaultAction = 'profile';

    public function filters()
    {
        return array(
            'accessControl',
        );
    }

    public function accessRules()
    {
        return array(
            array('allow',
                'actions' => array('captcha'),
                'users' => array('*'),
            ),
            array('allow',
                'actions' => array('login', 'logout', 'activation'),
                'users' =>array('*'),
            ),
            array('allow',
                'actions' => array('resetPassword', 'changePassword'),
                'users' =>array('*'),
                'expression' => 'isset(Yii::app()->params["allowResetPassword"]) && Yii::app()->params["allowResetPassword"]',
            ),
            array('allow',
                'actions' => array('profile'),
                'users'=>array('@'),
            ),

            array('deny',
                'users'=>array('*'),
            ),
        );
    }

    public function actions() {
        return array(
            'captcha'=>array(
                'class'=>(isset(Yii::app()->params['captchaAction']) && Yii::app()->params['captchaAction']) ? Yii::app()->params['captchaAction'] : 'CCaptchaAction',
                'backColor' => 0xffffff,
                'transparent' => true,
            ),
        );
    }

    public function actionLogin()
    {
        if (Yii::app()->user->isGuest) {
            $model=new UserLoginForm;

            if(isset($_POST[get_class($model)]))
            {
                $model->attributes=$_POST[get_class($model)];

                if ($model->validate()) {
                    if (Yii::app()->user->returnUrl)
                        $this->redirect(Yii::app()->user->returnUrl);
                    else
                        $this->redirect(Yii::app()->homeUrl);
                }
            }

            $this->render('login', array('model'=>$model));
        } else {
            if (Yii::app()->user->isAdmin())
                $this->redirect(array('/admin/index/index'));
            elseif (Yii::app()->user->isWorker())
                $this->redirect(array('/worker/index/index'));
            else
                $this->redirect(array('/client/index/index'));
        }
    }

    public function actionResetPassword()
    {
        if (!Yii::app()->user->isGuest)
            $this->redirect(array('user/changePassword'));

        $model = new UserResetPasswordForm();
        $data = Yii::app()->request->getPost(get_class($model));
        if (is_array($data) && $data) {
            $model->attributes = $data;
            if ($model->validate()) {
                $user = $model->getUser();
                if ($user) {
                    if ($key = $model->resetPasswordRequest()) {
                        $mail = new YiiMailer();
                        $mail->setView('user_reset_password');
                        $mail->setData(array(
                            'user' => $user,
                            'key' => $key,
                        ));
                        $mail->setTo($user->email);
                        $mail->send();
                    }

                    Yii::app()->user->setFlash('success', Yii::t('view_user_resetPassword', 'further instructions sent to you by email'));

                    $this->redirect(array('changePassword', 'email' => $user->email));

                    Yii::app()->end();
                }
            }
        }

        $this->render('reset_password', array(
            'model' => $model,
        ));
    }

    public function actionChangePassword($email = null, $token = null)
    {
        $model = new UserChangePasswordForm();

        if (Yii::app()->user->isGuest) {
            $displayVariant = 'long';

            if ($email)
                $model->email = $email;

            if ($email && $token) {
                $model->key = $token;
                if ($model->validate(array('email','token')))
                    $displayVariant = 'short';
            }
        } else
            $displayVariant = 'user';

        $params = Yii::app()->request->getPost(get_class($model));

        if (is_array($params) && $params) {
            $model->attributes = $params;
            $user = $model->getUser();
            if ($user && $model->validate()) {
                $user->password_hash = $user->hashPassword($model->newPassword);
                $user->security_token = '';
                $user->auth_key = '';
                $user->save(false);

                Yii::app()->user->setFlash('success', Yii::t('view_user_changePassword', 'password successfully changed'));

                $mail = new YiiMailer();
                $mail->setView('user_change_password');
                $mail->setData(array(
                    'user' => $user,
                    'password' => $model->newPassword
                ));
                $mail->setTo($user->email);
                $mail->send();

                $this->redirect(array('login'));

                Yii::app()->end();
            }
        }

        $this->render('change_password', array(
            'model' => $model,
            'displayVariant' => $displayVariant,
        ));
    }

    public function actionActivation($email = null, $token = null)
    {
        $model = new UserActivationForm;

        $data = Yii::app()->request->getPost(get_class($model));
        if (!is_array($data)) {
            $data = array();
            if ($email)
                $data['email'] = $email;
            if ($token)
                $data['activationToken'] = $token;
        }

        if (is_array($data) && $data) {
            $model->attributes = $data;
            if ($model->validate()) {
                $user = $model->activateUser();
                if ($user) {
                    Yii::app()->user->setFlash('success', Yii::t('view_user_activation', 'user successfully activated'));
                    if (Yii::app()->user->returnUrl)
                        $this->redirect(Yii::app()->user->returnUrl);
                    else
                        $this->redirect(array());
                } else
                    $model->addError('email', Yii::t('view_user_activation', 'Activation error'));
            }
        }

        $this->render('activation', array(
            'model' => $model,
        ));
    }

    public function actionChangeEmail($email = null, $new_email = null, $token = null)
    {
        $model = new UserChangeEmailForm;

        $data = Yii::app()->request->getPost(get_class($model));
        if (!is_array($model)) {
            $data = array();
            if ($email)
                $data['email'] = $email;
            if ($new_email)
                $data['new_email'] = $new_email;
            if ($token)
                $data['activationToken'] = $token;
        }

        if (is_array($data) && $data) {
            $model->attributes = $data;
            if ($model->validate()) {
                $user = $model->changeEmail();
                if ($user) {
                    Yii::app()->user->setFlash('success', Yii::t('view_user_change_email', 'email successfully changed'));
                    $this->redirect(array('user/profile'));
                } else
                    $model->addError('new_email', Yii::t('view_user_change_email', 'Email change error'));
            }
        }

        $this->render('change_email', array(
            'model' => $model,
        ));
    }

    public function actionLogout()
    {
        if (!Yii::app()->user->isGuest) {
            Yii::app()->user->logout();
        }
        Yii::app()->user->logout();
        $this->redirect(Yii::app()->homeUrl);
    }

    public function actionProfile()
    {

    }

}