<?php


class SettingsForm extends CFormModel
{

    public $allowResetPassword;
    public $enableCaptcha;
    public $postDeliveryEmailList;
    public $postDeliveryFileTypes;
    public $postDeliveryMaxFileSize;
    public $cseLogin;
    public $csePassword;
    public $cseDefaultCountry;
    public $cseDefaultUrgency;
    public $cseCompany;
    public $cseContactPerson;
    public $cseNotifyAppealDepartaments;

    public $clientIndexPageContent = '';
    public $clientDebtLimitPageContent = '';
    public $workerIndexPageContent = '';

    protected $cseModel;

    public function rules()
    {
        return array(
            array('allowResetPassword, enableCaptcha, postDeliveryMaxFileSize, cseLogin, csePassword', 'required'),
            array('postDeliveryEmailList', 'EmailListValidator'),
            array('postDeliveryFileTypes', 'length', 'max' => 20),
            array('postDeliveryMaxFileSize', 'numerical', 'min' => 0, 'max' => 999999),
            array('cseLogin, csePassword', 'length', 'max' => 100),
            array('cseDefaultCountry', 'safe'),
            array('cseDefaultUrgency', 'in', 'range' => array_keys($this->getCseUrgencyLabels())),
            array('cseCompany', 'in', 'range' => array_keys($this->getCseCompanyLabels()), 'allowEmpty' => true),
            array('cseContactPerson','in', 'range' => array_keys($this->getCseContactPersonLabels()), 'allowEmpty' => true),
            array('cseNotifyAppealDepartaments', 'ArrayValidator', 'allowEmpty' => true, 'validator' => 'numerical'),

            array('clientIndexPageContent, workerIndexPageContent, clientDebtLimitPageContent', 'safe'),

            array('allowResetPassword, enableCaptcha', 'in', 'range' => array_keys($this->getStatusLabels())),
        );
    }

    public function init()
    {
        $this->cseModel = new CseDelivery();
    }

    public function getStatusLabels()
    {
        return StatusHelper::getLabels();
    }

    public function getStatusLabel()
    {
        return StatusHelper::getLabel($this->status);
    }

    public function attributeLabels()
    {
        return array(
            'allowResetPassword' => Yii::t('model_settingsForm', 'allow reset password'),
            'enableCaptcha' => Yii::t('model_settingsForm', 'captcha'),
            'postDeliveryEmailList' => Yii::t('model_settingsForm', 'post delivery email list'),
            'postDeliveryFileTypes' => Yii::t('model_settingsForm', 'post delivery file types'),
            'postDeliveryMaxFileSize' => Yii::t('model_settingsForm', 'post delivery max file size'),
            'cseLogin' => Yii::t('model_settingsForm', 'cse login'),
            'csePassword' => Yii::t('model_settingsForm', 'cse password'),
            'cseDefaultCountry' => Yii::t('model_settingsForm', 'cse default country'),
            'clientIndexPageContent' => Yii::t('model_settingsForm', 'client index page content'),
            'workerIndexPageContent' => Yii::t('model_settingsForm', 'worker index page contnet'),
            'clientDebtLimitPageContent' => Yii::t('model_settingsForm', 'client debt limit page content'),
            'cseDefaultUrgency' => Yii::t('model_settingsForm', 'cse default urgency'),
            'cseCompany' => Yii::t('model_settingsForm', 'cse company'),
            'cseContactPerson' => Yii::t('model_settingsForm', 'cse contact person'),
            'cseNotifyAppealDepartaments' => Yii::t('model_settingsForm', 'cse notify appeal departaments'),
        );
    }

    public function getCseCompanyLabels()
    {
        $result = Yii::app()->cache->get('cse_company_labels');
        if (!$result) {
            $result = Yii::app()->cseApi->getCompanies();
            Yii::app()->cache->set('cse_company_labels', $result, 5*24*60*60);
        }
        return $result;
    }

    public function getCseContactPersonLabels()
    {
        $result = Yii::app()->cache->get('cse_contact_person_labels');
        if (!$result) {
            $result = Yii::app()->cseApi->getContactPersons();
            Yii::app()->cache->set('cse_contact_person_labels', $result, 5*24*60*60);
        }
        return $result;
    }

    public function getCseUrgencyLabels()
    {
        return $this->cseModel->getAvailableUrgencyLabels();
    }

}