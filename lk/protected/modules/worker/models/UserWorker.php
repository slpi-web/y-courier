<?php

class UserWorker extends User
{

    public $password;

    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public function rules()
    {
        $rules =  array(
            array('comment', 'safe'),

            array('id, email, display, status', 'safe', 'on'=>'search'),
        );

        $rules = CMap::mergeArray($rules, array(
            array('debt, business_centers', 'required'),
            array('additional_email', 'length', 'max'=>100),
            array('additional_email', 'email', 'allowEmpty' => true),
            array('debt', 'numerical', 'integerOnly' => false, 'min' => 0),
            array('business_centers', 'ArrayValidator', 'splitBy'=> ',', 'allowEmpty' => false, 'validator' => 'numerical'),
        ));

        if ($this->getIsNewRecord()) {
            $rules = CMap::mergeArray(array(
                array('email, password, status, organization, inn, phone, debt_limit', 'required'),
                array('status', 'numerical', 'integerOnly'=>true),
                array('email', 'length', 'max'=>100),
                array('organization', 'length', 'max'=>100),
                array('inn', 'numerical', 'integerOnly'=>true, 'allowEmpty' => true),
                array('inn', 'length', 'min' => 10, 'max' => 12, 'allowEmpty' => true),
                array('phone', 'length', 'max' => 100, 'allowEmpty'=>true),
                array('debt_limit', 'numerical', 'integerOnly' => false, 'min' => 0),
                array('password', 'length', 'max'=> 64),
            ), $rules);
        }

        return $rules;
    }

    public function init()
    {
        parent::init();
        $this->type = self::TYPE_CLIENT;
        $this->status = self::STATUS_ACTIVE;
    }

    public function behaviors()
    {
        return array(
        );
    }

    public function afterFind()
    {
        parent::afterFind();
    }

    public function loadRelations()
    {
        switch ($this->type) {
            case self::TYPE_CLIENT:
                $this->business_centers = $this->getBusinessCentersId();
                break;
            case self::TYPE_WORKER:
                $this->appeal_departaments = $this->getAppealDepartamentsId();
                break;
        }
    }

    public function attributeLabels()
    {
        $labels = parent::attributeLabels();
        $labels['password'] = $labels['password_hash'];
        return $labels;
    }

    protected function beforeSave()
    {
        if (parent::beforeSave()) {
            if ($this->getIsNewRecord() && $this->password) {
                $this->password_hash = $this->hashPassword($this->password);
            }
            return true;
        }
        return false;
    }

    public function search()
    {
        $this->type = self::TYPE_CLIENT;

        return parent::search();
    }

} 