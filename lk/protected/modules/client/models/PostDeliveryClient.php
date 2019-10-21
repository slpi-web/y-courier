<?php

class PostDeliveryClient extends PostDelivery
{

    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public function rules()
    {
        return CMap::mergeArray(parent::rules(), array(
            array('business_center_id, office, letters_count', 'required'),
            array('letters_count', 'numerical', 'integerOnly'=>true, 'max' => 99999),
            array('business_center_id', 'length', 'max'=>11),
            array('office', 'length', 'max'=>50),
            array('comment', 'safe'),

            array('organization, business_center_id, timestamp, office, letters_count', 'safe', 'on'=>'search'),
        ));
    }

    protected function beforeSave()
    {
        if (parent::beforeSave()) {

            $user = $this->user;
            if ($user)
                $this->organization = $user->organization;

            return true;
        }
        return false;
    }

}