<?php

class AppealClient extends Appeal
{

    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public function rules()
    {
        return array(
            array('business_center_id, appeal_departaments, subject, text', 'required'),
            array('user_id, business_center_id', 'length', 'max'=>11),
            array('subject', 'length', 'max'=>255),
            array('appeal_departaments', 'ArrayValidator', 'allowEmpty' => false, 'validator' => 'numerical', 'convertToArray' => true),

            array('id, appeal_departaments, business_center_id, timestamp, status, subject', 'safe', 'on'=>'search'),
        );
    }

    public function search()
    {
        $criteria = $this->getSearchCriteria();

        $criteria->with = array(
            'businessCenter' => array(
                'select' => array('id', 'caption'),
            ),
            'appealDepartaments' => array(
                'select' => array('id', 'caption'),
            ),
        );

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }

}