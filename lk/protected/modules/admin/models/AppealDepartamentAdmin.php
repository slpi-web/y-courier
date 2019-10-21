<?php

class AppealDepartamentAdmin extends AppealDepartament
{

    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public function rules()
    {
        return array(
            array('status, caption', 'required'),
            array('status', 'in', 'range' => array_keys($this->getStatusLabels())),
            array('caption', 'length', 'max'=>100),
            array('email_list', 'EmailListValidator'),

            array('status, caption', 'safe', 'on'=>'search'),
        );
    }

}