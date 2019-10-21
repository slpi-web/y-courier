<?php

class CseAddressClassifierForm extends CFormModel
{

    public $country_id;
    public $region_id;
    public $area_id;

    public function rules()
    {
        return array();
    }

    public function init()
    {
        if (isset(Yii::app()->params['cseDefaultCountry'])) {
            $this->country_id = Yii::app()->params['cseDefaultCountry'];
        }
    }

    public function attributeLabels()
    {
        return array(
            'country_id' => Yii::t('model_cseAddressClasifier', 'country'),
            'region_id' => Yii::t('model_cseAddressClasifier', 'region'),
            'area_id' => Yii::t('model_cseAddressClasifier', 'area'),
        );
    }

    public function getCountryModel()
    {
        if ($this->country_id)
            return CseCountry::model()->findByPk($this->country_id);

        return null;
    }

}