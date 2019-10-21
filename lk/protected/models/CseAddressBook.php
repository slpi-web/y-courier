<?php

class CseAddressBook extends BaseCseAddressBook
{

    const TYPE_SENDER = 0;
    const TYPE_RECIPIENT = 1;

    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public function rules()
    {
        return array(
            array('name, city, address, phone', 'required'),
            array('post_index', 'length', 'min' => 3, 'max' => 12, 'allowEmpty' => true),
            array('name', 'length', 'min' => 3, 'max' => 100),
            array('contact', 'length', 'min' => 3, 'max' => 100, 'allowEmpty' => true),
            array('phone', 'length', 'max'=>100, 'allowEmpty' => true),
            array('city', 'exist', 'attributeName' => 'id', 'className' => 'CseCity', 'criteria' => array('scopes' => array('active', 'typeLocality')), 'allowEmpty' => false),
            array('address', 'length', 'min' => 3, 'max' => 200, 'allowEmpty' => false),
            array('email', 'email', 'allowEmpty' => true),
            array('info', 'safe'),

            array('type', 'in', 'range' => array_keys($this->getTypeLabels()), 'on' => 'add'),

            array('type, name, address, phone', 'safe', 'on' => 'search'),
        );
    }

    public function relations()
    {
        return array(
            'user' => array(self::BELONGS_TO, 'User', 'user_id'),
            'cityModel' => array(self::BELONGS_TO, 'CseCity', 'city'),
        );
    }

    public function getTypeLabels()
    {
        return array(
            self::TYPE_SENDER  => Yii::t('model_cse_address_book', 'type - sender'),
            self::TYPE_RECIPIENT => Yii::t('model_cse_address_book', 'type - recipient'),
        );
    }

    public function getTypeLabel()
    {
        $labels = $this->getTypeLabels();
        if (isset($labels[$this->type]))
            return $labels[$this->type];
        return '';
    }

    public function setType($type)
    {
        if (!is_numeric($type)) {
            if ($type == 'recipient')
                $this->type = self::TYPE_RECIPIENT;
            if ($type == 'sender')
                $this->type = self::TYPE_SENDER;
        } else
            $this->type = $type;

        return $this->type;
    }

    protected function beforeValidate()
    {
        $this->setType($this->type);

        return parent::beforeValidate();
    }

    public function getFormDataJson()
    {
        $options = $this->getAttributes(array('name', 'contact', 'city', 'address', 'post_index', 'phone', 'email', 'info'));

        if ($this->cityModel) {
            $options['city'] = array(
                'id' => $this->cityModel->id,
                'text' => $this->cityModel->caption,
            );
            $options['country_id'] = $this->cityModel->country_id;
        }

        if (function_exists('json_encode'))
            $options = json_encode($options);
        else
            $options = CJSON::encode($options);
        return $options;
    }

    public function search()
    {
        $criteria=new CDbCriteria;

        $criteria->compare('user_id', $this->user_id);
        $criteria->compare('type', $this->type);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('contact', $this->contact, true);
        $criteria->compare('address',$this->address,true);
        $criteria->compare('post_index',$this->post_index,true);
        $criteria->compare('phone',$this->phone,true);
        $criteria->compare('email',$this->email,true);
        $criteria->compare('info',$this->info,true);

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }

}