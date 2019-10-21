<?php

class UserActivationForm extends CFormModel
{

    public $email = '';
    public $activationToken = '';

    public function rules()
    {
        return array(
            array('email, activationToken', 'required'),
            array('email', 'email'),
            array('email', 'userActivation'),
        );
    }

    public function attributeLabels()
    {
        return array(
            'email' => Yii::t('model_user', 'email'),
            'activationToken' => Yii::t('model_user', 'email_validate_token'),
        );
    }

    public function userActivation($attribute, $params)
    {
        $user = User::findUser($this->email);
        if ($user) {
            if (!$user->password_hash)
                $this->addError('email', Yii::t('model_siteUserActivationForm', 'errorUserUnregistered'));
            else {
                if ($user->status != User::STATUS_INACTIVE)
                    $this->addError('email', Yii::t('model_siteUserActivationForm', 'errorUserAlreadyActivated'));
                else {
                    if (!$user->validateSecurityToken($this->activationToken, 'a'))
                        $this->addError('activationToken', Yii::t('model_siteUserActivationForm', 'errorWrongActivationToken'));
                }
            }
        } else
            $this->addError('email', Yii::t('model_siteUserActivationForm', 'errorUserDoesNotExists'));
    }

    public function activateUser()
    {
        $user = User::findUser($this->email);
        if ($user->status == User::STATUS_INACTIVE) {
            if ($user->validateSecurityToken($this->activationToken, 'a')) {
                $user->cleanSecurityToken();
                $user->status = User::STATUS_ACTIVE;
                $user->save(false);
                return $user;
            }
        }
        return false;
    }

}