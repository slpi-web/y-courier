<?php
/**
 * Created by PhpStorm.
 * User: dim
 * Date: 09.06.14
 * Time: 10:57
 */

class CaptchaFormModel extends CFormModel
{

    public $verifyCode;

    public $captchaAction = 'captcha';

    public static function isCaptchaEnabled()
    {
        if (CCaptcha::checkRequirements() && (!isset(Yii::app()->params['enableCaptcha']) || Yii::app()->params['enableCaptcha']))
            return true;
        return false;
    }

    public function rules()
    {
        $rules = array();
        if (self::isCaptchaEnabled()) {
            $rules = array(
                array('verifyCode', 'required'),
                array('verifyCode', 'captcha', 'allowEmpty' => false, 'captchaAction' => $this->captchaAction)
            );
        }
        return $rules;
    }

    public function attributeLabels()
    {
        $labels['verifyCode'] = Yii::t('model_captchaFormModel', 'verifyCode');
        return $labels;
    }

} 