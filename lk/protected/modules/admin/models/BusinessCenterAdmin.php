<?php

class BusinessCenterAdmin extends BusinessCenter
{

    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public function rules()
    {
        return array(
            array('caption', 'required'),
            array('caption, address', 'length', 'max'=>255),
            array('phone', 'length', 'max' => 100, 'allowEmpty'=>true),

            array('caption, phone, address', 'safe', 'on'=>'search'),
        );
    }

} 