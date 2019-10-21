<?php

class UserResetPasswordForm extends CaptchaFormModel
{

    public $email = '';

    protected $user = null;

    public function rules()
    {
        return CMap::mergeArray(parent::rules(), array(
            array('email', 'required'),
            array('email', 'email'),
            array('email', 'validateUser'),
        ));
    }

    public function attributeLabels()
    {
        return CMap::mergeArray(parent::attributeLabels(), array(
            'email' => Yii::t('model_user', 'email'),
        ));
    }

    public function validateUser($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $this->user = User::findUser($this->email);
            if (!$this->user) {
                $this->addError('email', Yii::t('model_resetPasswordForm', 'error - wrong email'));
            } else {
                if ($this->user->status == User::STATUS_BANNED)
                    $this->addError('email', Yii::t('model_resetPasswordForm', 'error - user banned'));
            }
        }
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    public function resetPasswordRequest()
    {
        if ($this->user) {
            if ($this->user->status == User::STATUS_INACTIVE) {
            } else {
                $token = $this->user->generateSecurityToken('p');
                $this->user->saveAttributes(array(
                    'security_token' => $token,
                ));
            }
            return $token;
        }
        return false;
    }

}