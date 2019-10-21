<?php

class UserAdmin extends User
{

    public $password;

    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public function rules()
    {
        $rules =  array(
            array('email, status, type', 'required'),
            array('status, type', 'numerical', 'integerOnly'=>true),
            array('email', 'length', 'max'=>100),
            array('password', 'length', 'max'=>64),
            array('comment', 'safe'),

            array('id, email, display, status, type', 'safe', 'on'=>'search'),
        );

        if ($this->type == self::TYPE_CLIENT) {
            $rules = CMap::mergeArray($rules, array(
                array('organization, inn, phone, debt, debt_limit', 'required'),
                array('organization, additional_email', 'length', 'max'=>100),
                array('inn', 'numerical', 'integerOnly'=>true, 'allowEmpty' => true),
                array('inn', 'length', 'min' => 10, 'max' => 12, 'allowEmpty' => true),
                array('phone', 'length', 'max' => 100, 'allowEmpty'=>true),
                array('additional_email', 'email', 'allowEmpty' => true),
                array('debt, debt_limit', 'numerical', 'integerOnly' => false, 'min' => 0),
                array('business_centers', 'ArrayValidator', 'splitBy'=>',', 'allowEmpty' => false, 'validator' => 'numerical'),
            ));
        } elseif ($this->type == self::TYPE_WORKER) {
            $rules = CMap::mergeArray($rules, array(
                array('appeal_departaments', 'ArrayValidator', 'allowEmpty' => false, 'validator' => 'numerical'),
            ));
        }

        return $rules;
    }

    public function behaviors()
    {
        return array(
            'preset' => array(
                'class' => 'ext.ActiveRecord.PresetAttributesActiveRecordBehavior',
                'safePresetAttributes' => array(
                    'type',
                ),
            )
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
            if ($this->password) {
                $this->password_hash = $this->hashPassword($this->password);
            }
            return true;
        }
        return false;
    }

} 