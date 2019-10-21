<?php

class PostDeliveryWorker extends PostDelivery
{

    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public function rules()
    {
        return CMap::mergeArray(parent::rules(), array(
            array('user_id, business_center_id, office, letters_count, status', 'required'),
            array('status', 'in', 'range' => array_keys($this->getStatusLabels())),
            array('letters_count', 'numerical', 'integerOnly'=>true, 'max' => 99999),
            array('user_id, business_center_id', 'length', 'max'=>11),
            array('office', 'length', 'max'=>50),
            array('comment', 'safe'),

            array('id, user_id, organization, business_center_id, timestamp, office, letters_count', 'safe', 'on'=>'search'),
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