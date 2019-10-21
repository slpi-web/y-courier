<?php

class CseDeliveryWorker extends CseDelivery
{

    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public function rules()
    {
        return CMap::mergeArray(parent::rules(), array(
            array('user_id', 'required'),
            array('user_id', 'length', 'max'=>11),
            //array('user_id', 'exists', 'attributeName' => 'id', 'className' => 'User', 'criteria' => array('scopes' => array('client')), 'allowEmpty' => false),

            array('user_id', 'safe', 'on'=>'search'),
        ));
    }

}