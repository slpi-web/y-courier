<?php

class UserLoginForm extends CaptchaFormModel
{

    public $email;
    public $password;
    public $rememberMe;

    private $userIdentity;

    private $adminOnly = false;

    public function rules()
    {
        return CMap::mergeArray(parent::rules(), array(
            array('email, password', 'required'),
            array('rememberMe', 'boolean'),
            array('password', 'authenticate'),
        ));
    }

    public function attributeLabels()
    {
        return CMap::mergeArray(parent::attributeLabels(), array(
            'email' => Yii::t('model_user', 'email'),
            'password' => Yii::t('model_user', 'password'),
            'rememberMe' => Yii::t('model_user', 'rememberMe'),
        ));
    }

    public function allowAdminOnly()
    {
        $this->adminOnly = true;
    }

    public function authenticate($attribute, $params)
    {
        if (!$this->hasErrors())
        {
            $this->userIdentity=new UserIdentity($this->email,$this->password);
            if ($this->adminOnly)
                $this->userIdentity->allowAdminOnly();
            if(!$this->userIdentity->authenticate()) {
                switch ($this->userIdentity->errorCode) {
                    case UserIdentity::ERROR_STATUS_BANNED:
                        $this->addError('email', Yii::t('model_userLoginForm', 'ERROR - User Banned'));
                        break;
                    case UserIdentity::ERROR_STATUS_INACTIVE:
                        $this->addError('email', Yii::t('model_userLoginForm', 'ERROR - User Not Active'));
                        break;
                    case UserIdentity::ERROR_PASSWORD_INVALID:
                        $this->addError('email', Yii::t('model_userLoginForm', 'ERROR - Password Invalid'));
                        break;
                    case UserIdentity::ERROR_USERNAME_INVALID:
                        $this->addError('email', Yii::t('model_userLoginForm', 'ERROR - Username Invalid'));
                        break;
                    case UserIdentity::ERROR_UNKNOWN_IDENTITY:
                        $this->addError('email', Yii::t('model_userLoginForm', 'ERROR - Unknown Identity'));
                        break;
                    case UserIdentity::ERROR_NOT_ADMIN:
                        $this->addError('email', Yii::t('model_userLoginForm', 'ERROR - Not Admin'));
                        break;
                }
            } else {
                $duration=$this->rememberMe ? 3600*24*15 : 0;
                Yii::app()->user->login($this->userIdentity, $duration);
            }
        }
    }


}