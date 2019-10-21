<?php

class CseDeliveryCalcForm extends CFormModel
{

    public $sender_city;
    public $recipient_city;
    public $cargo_type;
    public $cargo_weight;
    public $urgency_id;
    public $cargo_seats_number;

    protected $cseModel = null;

    public function init()
    {
        $this->cseModel = new CseDelivery();
        $this->cargo_weight = 0.1;
        $this->cargo_seats_number = 1;
    }

    public function rules()
    {
        return array(
            array('sender_city, recipient_city, cargo_type, cargo_seats_number, cargo_weight', 'required'),

            array('sender_city, recipient_city', 'exist', 'attributeName' => 'id', 'className' => 'CseCity', 'criteria' => array('scopes' => array('active', 'typeLocality')), 'allowEmpty' => false),
            array('cargo_type', 'in', 'range' => array_keys($this->getCargoTypeLabels()), 'allowEmpty' => 'false'),
            array('cargo_seats_number', 'numerical', 'min' => 1, 'max' => 6000, 'integerOnly' => true, 'allowEmpty' => false),
            array('cargo_weight', 'numerical', 'min' => 0, 'max' => 10000, 'integerOnly' => false, 'allowEmpty' => false),

            array('urgency_id', 'in', 'range' => array_keys($this->getAvailableUrgencyLabels()), 'allowEmpty' => false),
        );
    }

    public function attributeLabels()
    {
        $labels = array();
        if ($this->cseModel) {
            $labels = $this->cseModel->attributeLabels();
        }
        return $labels;
    }

    public function getCargoTypeLabels()
    {
        $labels = array();
        if ($this->cseModel)
            $labels = $this->cseModel->getCargoTypeLabels();
        return $labels;
    }

    public function getAvailableUrgencyLabels()
    {
        $labels = array();
        if ($this->cseModel) {
            $labels = CMap::mergeArray(array(
                '' => Yii::t('view_cse_calc', 'urgency - any'),
            ),$this->cseModel->getAvailableUrgencyLabels());
        }
        return $labels;
    }

    public function calculate()
    {
        $response = Yii::app()->cseApi->calc($this->sender_city, $this->recipient_city, $this->cargo_type, $this->cargo_weight, $this->urgency_id, '', $this->cargo_seats_number);
        return $response;
    }

    public function renderResponse($view, $data = array())
    {
        $data['cseDeliveryCalc'] = $this;
        $data['cseDeliveryCalcResponse'] = $this->calculate();
        Yii::app()->getController()->render($view, $data);
    }


}