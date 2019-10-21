<?php

class UserChangePasswordForm extends CaptchaFormModel
{

    public $email;
    public $key;
    public $oldPassword;
    public $newPassword;

    protected $user = null;

    public function rules()
    {
        if (Yii::app()->user->isGuest) {
            $rules = array(
                array('email, key, newPassword', 'required'),
                array('email', 'validateGuestUser'),
                array('newPassword', 'length', 'min'=>6),
            );
        } else {
            $rules = array(
                array('oldPassword, newPassword', 'required'),
                array('oldPassword', 'validateSignedUser'),
                array('newPassword', 'length', 'min'=>6),
            );
        }

        return CMap::mergeArray(parent::rules(), $rules);
    }

    public function attributeLabels()
    {
        return CMap::mergeArray(array(
            'email' => Yii::t('model_user', 'email'),
            'key' => Yii::t('model_user', 'change_password_key'),
            'oldPassword' => Yii::t('model_user', 'old_password'),
            'newPassword' => Yii::t('model_user', 'new_password'),
        ), parent::attributeLabels());
    }

    public function validateSignedUser($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = Yii::app()->user->loadUser();
            if ($user) {
                if (!$user->validatePassword($this->oldPassword))
                    $this->addError('oldPassword', Yii::t('model_userChangePasswordForm', 'error - wrong old password'));
            } else
                $this->addError('email', Yii::t('model_userChangePasswordForm', 'error - wrong email'));
        };
    }

    public function validateGuestUser($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = User::findUser($this->email);
            if ($user) {
                if ($user->validateSecurityToken($this->key, 'p')) {
                    if ($user->status == User::STATUS_BANNED)
                        $this->addError('email', Yii::t('model_userChangePasswordForm', 'error - user banned'));
                } else
                    $this->addError('key', Yii::t('model_userChangePasswordForm', 'error - wrong change password key'));
            } else
                $this->addError('email', Yii::t('model_userChangePasswordForm', 'error - wrong email'));
        }
    }

    public function getUser()
    {
        if (Yii::app()->user->isGuest)
            return User::findUser($this->email);
        else
            return Yii::app()->user->loadUser();
    }
}