<?php

class AppealAdmin extends Appeal
{

    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public function rules()
    {
        return array(
            array('user_id, business_center_id, appeal_departaments, status, subject, text', 'required'),
            array('status', 'in', 'range' => array_keys($this->getStatusLabels())),
            array('user_id, business_center_id', 'length', 'max'=>11),
            array('subject', 'length', 'max'=>255),
            array('appeal_departaments', 'ArrayValidator', 'allowEmpty' => false, 'validator' => 'numerical'),

            array('id, user_id, appeal_departaments, business_center_id, timestamp, status, subject', 'safe', 'on'=>'search'),
        );
    }

    public function search()
    {
        $criteria = $this->getSearchCriteria();

        $criteria->with = array(
            'user' => array(
                'select' => array('id', 'display'),
            ),
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