<?php

class CseDeliveryAdmin extends CseDelivery
{

    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public function rules()
    {
        $rules = CMap::mergeArray(parent::rules(), array(
            array('user_id, status', 'required'),
            array('user_id', 'length', 'max'=>11),
            //array('user_id', 'exists', 'attributeName' => 'id', 'className' => 'User', 'criteria' => array('scopes' => array('client')), 'allowEmpty' => false),
            array('status', 'in', 'range' => array_keys($this->getStatusLabels())),
            array('client_status', 'in', 'range' => array_keys($this->getClientStatusLabels())),

            array('user_id', 'safe', 'on'=>'search'),
        ));

        if ($this->status == self::STATUS_SYNCED) {
            $rules = CMap::mergeArray($rules, array(
                array('cse_id', 'required'),
                array('price', 'numerical', 'integerOnly' => false, 'min' => 0),
                array('cse_id', 'length', 'max' => 20),
            ));
        }

        return $rules;
    }

    public function behaviors()
    {
        $behaviors =  parent::behaviors();

        $behaviors['preset'] = array(
                'class' => 'ext.ActiveRecord.PresetAttributesActiveRecordBehavior',
                'safePresetAttributes' => array(
                    'payer', 'status'
                ),
            );

        return $behaviors;
    }

}