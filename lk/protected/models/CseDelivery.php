<?php

class CseDelivery extends BaseCseDelivery
{

    const STATUS_NOT_VERIFIED = 0;
    const STATUS_SYNC = 1;
    const STATUS_NOT_SYNCED = 2;
    const STATUS_SYNCED = 3;

    const CLIENT_STATUS_ACCEPTED = 0;
    const CLIENT_STATUS_IN_WORK = 1;
    const CLIENT_STATUS_DELIVERED = 2;

    const CARGO_TYPE_DOCUMENTS = '81dd8a13-8235-494f-84fd-9c04c51d50ec';
    const CARGO_TYPE_CARGO = '4aab1fc6-fc2b-473a-8728-58bcd4ff79ba';
    const CARGO_TYPE_OVERSIZED_CARGO = 'f132d9fa-a944-4c11-9001-f4dfdd13b4a7';
    const CARGO_TYPE_DANGEROUS_CARGO = 'dd80f922-a010-422a-b26a-0a65a6f894ce';

    const DELIVERY_METHOD_UP_DOORS = 1;
    const DELIVERY_METHOD_COD = 0;
    const DELIVERY_METHOD_POST_ROOM = 8;
    const DELIVERY_METHOD_WITH_RETURN = 2;
    const DELIVERY_METHOD_WITH_RETURN_AND_NOTIFY = 4;
    const DELIVERY_METHOD_WITH_NOTIFY = 3;
    const DELIVERY_METHOD_PICKUP = 5;
    const DELIVERY_METHOD_WAREHOUSE_TO_DOOR = 6;
    const DELIVERY_METHOD_WAREHOUSE_TO_WAREHOUSE = 7;

    const PAYER_CUSTOMER = 0;
    const PAYER_SENDER = 1;
    const PAYER_RECEIVER = 2;

    const PAYMENT_METHOD_CASHLESS = 1;
    const PAYMENT_METHOD_CASH = 0;

    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public function rules()
    {
        return array(
            array('sender, sender_city, sender_address, sender_phone, recipient, recipient_city, recipient_address, recipient_phone, cargo_type, cargo_seats_number, cargo_weight, delivery_method, urgency_id, payer, payment_method', 'required'),
            //array('sender_post_index, recipient_post_index', 'numerical', 'integerOnly'=>true, 'allowEmpty' => true),
            array('sender_post_index, recipient_post_index', 'length', 'min' => 3, 'max' => 12, 'allowEmpty' => true),
            array('sender, recipient', 'length', 'min' => 3, 'max' => 100),
            array('sender_contact, recipient_contact', 'length', 'min' => 3, 'max' => 100, 'allowEmpty' => true),
            array('sender_phone, recipient_phone', 'length', 'max'=>100, 'allowEmpty' => true),
            array('notify_phone', 'length', 'max' => 20, 'allowEmpty' => true),
            array('sender_city, recipient_city', 'exist', 'attributeName' => 'id', 'className' => 'CseCity', 'criteria' => array('scopes' => array('active', 'typeLocality')), 'allowEmpty' => false),
            array('sender_address, recipient_address', 'length', 'min' => 3, 'max' => 200, 'allowEmpty' => false),
            array('sender_email, recipient_email, notify_email', 'email', 'allowEmpty' => true),
            array('sender_info, recipient_info', 'safe'),
            array('notify_phone', 'match', 'pattern'=>'/^\+7[ ](\(\d{3}\))[ ]([\d]{3}[\-][\d]{2}[\-][\d]{2})$/', 'allowEmpty' => true),

            array('customs_value, cargo_weight, cargo_height, cargo_width, cargo_length, insurance_rate, declared_value_rate', 'numerical', 'integerOnly' => false),
            array('cargo_type', 'in', 'range' => array_keys($this->getCargoTypeLabels()), 'allowEmpty' => 'false'),
            array('customs_currency', 'in', 'range' => array_keys($this->getCustomsCurrencyLabels()), 'allowEmpty' => true),
            array('cargo_seats_number', 'numerical', 'min' => 1, 'max' => 6000, 'integerOnly' => true, 'allowEmpty' => false),
            array('cargo_weight', 'numerical', 'min' => 0, 'max' => 10000, 'integerOnly' => false, 'allowEmpty' => false),
            array('cargo_width, cargo_height, cargo_length', 'numerical', 'min' => 0, 'max' => 10000, 'integerOnly' => false, 'allowEmpty' => true),
            array('cargo_description', 'safe'),

            array('delivery_method', 'in', 'range' => array_keys($this->getAvailableDeliveryMethodLabels()), 'allowEmpty' => false),
            array('urgency_id', 'in', 'range' => array_keys($this->getAvailableUrgencyLabels()), 'allowEmpty' => false),
            array('payer', 'in', 'range' => array_keys($this->getPayerLabels(false)), 'allowEmpty' => false),
            array('payment_method', 'in', 'range' => array_keys($this->getAvailablePaymentMethodLabels()), 'allowEmpty' => false),
            array('take_date', 'date', 'format' => 'dd.MM.yyyy'),
            array('take_time_from, take_time_to', 'in', 'range' => array_keys($this->getTakeTimeLabels()), 'allowEmpty' => false),
            array('take_time_from', 'compare', 'compareAttribute' => 'take_time_to', 'operator' => '<', 'allowEmpty' => false),
            array('comment', 'safe'),

            array('insurance_rate, declared_value_rate', 'numerical', 'min' => 0, 'max' => 999999999, 'integerOnly' => false, 'allowEmpty' => true),


            array('sender, recipient, sender_city, recipient_city, client_status, status, timestamp', 'safe', 'on'=>'search'),
        );
    }

    public function relations()
    {
        return array(
            'urgency' => array(self::BELONGS_TO, 'CseUrgency', 'urgency_id'),
            'senderCity' => array(self::BELONGS_TO, 'CseCity', 'sender_city'),
            'recipientCity' => array(self::BELONGS_TO, 'CseCity', 'recipient_city'),
            'user' => array(self::BELONGS_TO, 'User', 'user_id'),
        );
    }

    public function behaviors()
    {
        return array(
            'preset' => array(
                'class' => 'ext.ActiveRecord.PresetAttributesActiveRecordBehavior',
                'safePresetAttributes' => array(
                    'payer',
                ),
            ),
            'cseDeliveryHelper' => array(
                'class' => 'ext.Cse.behaviors.CseDeliveryBehavior'
            ),
            'modelHistory' => array(
                'class' => 'ext.ModelHistory.behaviors.ModelHistoryBehavior',
                'modelName' => 'CseDelivery',
                'attributes' => array(
                    'id' => '$data',
                    'user_id' => '$model->user ? $model->user->display : ""',
                    'timestamp' => 'Yii::app()->dateFormatter->formatDateTime($data, "short", "short")',
                    'status' => '$model->getStatusLabel()',
                    'client_status' =>'$model->getClientStatusLabel()',
                    'cse_id' => '$data',
                    'price' => '$data',
                    'sender' => '$data',
                    'sender_contact' => '$data',
                    'sender_city' => '$model->senderCity ? $model->senderCity->caption : ""',
                    'sender_address' => '$data',
                    'sender_post_index' => '$data',
                    'sender_phone' => '$data',
                    'sender_email' => '$data',
                    'sender_info' => '$data',
                    'recipient' => '$data',
                    'recipient_contact' => '$data',
                    'recipient_city' => '$model->recipientCity ? $model->recipientCity->caption : ""',
                    'recipient_address' => '$data',
                    'recipient_post_index' => '$data',
                    'recipient_phone' => '$data',
                    'recipient_email' => '$data',
                    'recipient_info' => '$data',
                    'customs_value' => '$data',
                    'customs_currency' => '$model->getCustomsCurrencyLabel()',
                    'cargo_type' => '$model->getCargoTypeLabel()',
                    'cargo_seats_number' => '$data',
                    'cargo_weight' => '$data',
                    'cargo_height' => '$data',
                    'cargo_width' => '$data',
                    'cargo_length' => '$data',
                    'cargo_description' => '$data',
                    'delivery_method' => '$model->getDeliveryMethodLabel()',
                    'urgency_id' => '$model->urgency ? $model->urgency->caption : ""',
                    'payer' => '$model->getPayerLabel(false)',
                    'payment_method' => '$model->getPaymentMethodLabel()',
                    'take_date' => '$data',
                    'take_time_from' => '$data',
                    'take_time_to' => '$data',
                    'comment' => '$data',
                    'insurance_rate' => '$data',
                    'declared_value_rate' => '$data',
                    //'notify_phone' => '$data',
                    //'notify_email' => '$data',
                ),
            ),
        );
    }

    public function attributeLabels()
    {
        $labels = parent::attributeLabels();

        return $labels;
    }

    public function init()
    {
        $this->timestamp = time();
        $this->payer = self::PAYER_CUSTOMER;
        $this->status = self::STATUS_NOT_VERIFIED;
        $this->take_date = date('d.m.Y');
        $this->cargo_weight = 0.1;
        $this->cargo_seats_number = 1;
        $this->take_time_from = 9;
        $this->take_time_to = 18;
        if (isset(Yii::app()->params['cseDefaultUrgency']))
            $this->urgency_id = Yii::app()->params['cseDefaultUrgency'];
    }

    protected function afterFind()
    {
        $this->take_date = date('d.m.Y', strtotime($this->take_date));

        parent::afterFind();
    }

    protected function beforeSave()
    {
        if (parent::beforeSave()) {
            $this->take_date = date('Y-m-d', CDateTimeParser::parse($this->take_date,'dd.MM.yyyy'));
            return true;
        }
        return false;
    }

    protected function afterSave()
    {
        $this->take_date = date('d.m.Y', strtotime($this->take_date));

        if ($this->isNewRecord) {
            if (isset(Yii::app()->params['cseNotifyAppealDepartaments']) && is_array(Yii::app()->params['cseNotifyAppealDepartaments']) && !empty(Yii::app()->params['cseNotifyAppealDepartaments'])) {
                $criteria = new CDbCriteria;
                $criteria->addInCondition('id', Yii::app()->params['cseNotifyAppealDepartaments']);
                $emailList = array();
                $departaments = AppealDepartament::model()->findAll($criteria);
                foreach ($departaments as $departament) {
                    $emails = $departament->getEmails();
                    foreach ($emails as $email)
                        $emailList[$email] = 1;
                }
                $emailList = array_keys($emailList);
                if (count($emailList)) {
                    foreach ($emailList as $email) {
                        $mail = new YiiMailerQueue();
                        $mail->setLayout('mail_worker');
                        $mail->setView('new_cse_delivery_worker');
                        $mail->setData(array(
                            'model' => $this,
                        ));
                        $mail->setTo($email);
                        $mail->send();
                    }
                }
            }

        }

        parent::afterSave();
    }

    public function getStatusLabels()
    {
        return array(
            self::STATUS_NOT_VERIFIED => Yii::t('model_cse_delivery', 'status - not verified'),
            self::STATUS_SYNC => Yii::t('model_cse_delivery', 'status - sync'),
            self::STATUS_NOT_SYNCED => Yii::t('model_cse_delivery', 'status - not synced'),
            self::STATUS_SYNCED => Yii::t('model_cse_delivery', 'status - synced'),
        );
    }

    public function getAvailableStatusLabels()
    {
        $labels = $this->getStatusLabels();
        if (isset($labels[self::STATUS_SYNC]))
            unset($labels[self::STATUS_SYNC]);

        return $labels;
    }

    public function getClientStatusLabels()
    {
        return array(
            self::CLIENT_STATUS_ACCEPTED => Yii::t('model_cse_delivery', 'client status - accepted'),
            self::CLIENT_STATUS_IN_WORK => Yii::t('model_cse_delivery', 'client status - in_work'),
            self::CLIENT_STATUS_DELIVERED => Yii::t('model_cse_delivery', 'client status - deliered'),
        );
    }

    public function getClientStatusLabel()
    {
        $labels = $this->getClientStatusLabels();
        if (isset($labels[$this->client_status]))
            return $labels[$this->client_status];
        return '';
    }

    public function getStatusLabel()
    {
        $labels = $this->getStatusLabels();
        if (isset($labels[$this->status]))
            return $labels[$this->status];

        return '';
    }

    public function getCustomsCurrencyLabels()
    {
        return array(
            'ff3f7c38-4430-11dc-9497-0015170f8c09' => 'Рубль РФ',
            '84a60e1d-0b02-11e3-98e9-001e670c42dc' => 'Австралийский доллар',
            'a2e573b3-0b02-11e3-98e9-001e670c42dc' => 'Бахрейнский динар',
            'db0b0596-0b03-11e3-98e9-001e670c42dc' => 'Болгарский лев',
            'ce732e7f-0b02-11e3-98e9-001e670c42dc' => 'Гонконгский доллар',
            '793fb395-0b02-11e3-98e9-001e670c42dc' => 'Дирхам (ОАЭ)',
            'e6853795-4421-11dc-9497-0015170f8c09' => 'Доллар США',
            'e6853796-4421-11dc-9497-0015170f8c09' => 'Евро',
            'bdc7261e-0b02-11e3-98e9-001e670c42dc' => 'Египетский фунт',
            'dc27df02-0b02-11e3-98e9-001e670c42dc' => 'Индийская рупия',
            'e34e6364-0b02-11e3-98e9-001e670c42dc' => 'Иорданский динар',
            'a2e573b7-0b02-11e3-98e9-001e670c42dc' => 'Канадский доллар',
            'e11645df-0b03-11e3-98e9-001e670c42dc' => 'Катарский риал',
            'ea09ca9c-0b02-11e3-98e9-001e670c42dc' => 'Ливанский фунт',
            'd39d17d6-0b03-11e3-98e9-001e670c42dc' => 'Новозеландский доллар',
            'd4a6e63a-0b02-11e3-98e9-001e670c42dc' => 'Рупия',
            '0fa27cad-0b04-11e3-98e9-001e670c42dc' => 'Рэнд',
            'e73bb561-0b03-11e3-98e9-001e670c42dc' => 'Саудовский риял',
            'f43b7d0c-0b03-11e3-98e9-001e670c42dc' => 'Сингапурский доллар',
            'fb8f3b9d-0b03-11e3-98e9-001e670c42dc' => 'Сирийский фунт',
            'c6dcc951-0b02-11e3-98e9-001e670c42dc' => 'Фунт стерлингов',
            'aaeee71e-0b03-11e3-98e9-001e670c42dc' => 'Чешская крона',
            'a948c704-0b02-11e3-98e9-001e670c42dc' => 'Швейцарский франк',
        );
    }

    public function getCustomsCurrencyLabel()
    {
        $labels = $this->getCustomsCurrencyLabels();
        if (isset($labels[$this->customs_currency]))
            return $labels[$this->customs_currency];
        return '';
    }

    public function getCargoTypeLabels()
    {
        return array(
            self::CARGO_TYPE_DOCUMENTS => Yii::t('model_cse_delivery', 'cargo type - documents'),
            self::CARGO_TYPE_CARGO => Yii::t('model_cse_delivery', 'cargo type - cargo'),
            self::CARGO_TYPE_OVERSIZED_CARGO => Yii::t('model_cse_delivery', 'cargo type - oversized cargo'),
            self::CARGO_TYPE_DANGEROUS_CARGO => Yii::t('model_cse_delivery', 'cargo type - dangerous documents'),
        );
    }

    public function getCargoTypeLabel()
    {
        $labels = $this->getCargoTypeLabels();
        if (isset($labels[$this->cargo_type]))
            return $labels[$this->cargo_type];

        return '';
    }

    public function getDeliveryMethodLabels()
    {
        return array(
            self::DELIVERY_METHOD_UP_DOORS => Yii::t('model_cse_delivery', 'delivery method - up doors'),
            self::DELIVERY_METHOD_COD => Yii::t('model_cse_delivery', 'delivery method - cod'),
            self::DELIVERY_METHOD_POST_ROOM => Yii::t('model_cse_delivery', 'delivery method - post room'),
            self::DELIVERY_METHOD_WITH_RETURN => Yii::t('model_cse_delivery', 'delivery method - with return'),
            self::DELIVERY_METHOD_WITH_RETURN_AND_NOTIFY => Yii::t('model_cse_delivery', 'delivery method - with return and notify'),
            self::DELIVERY_METHOD_WITH_NOTIFY => Yii::t('model_cse_delivery', 'delivery method - with notify'),
            self::DELIVERY_METHOD_PICKUP => Yii::t('model_cse_delivery', 'delivery method - pickup'),
            self::DELIVERY_METHOD_WAREHOUSE_TO_DOOR => Yii::t('model_cse_delivery', 'delivery method - warehouse to door'),
            self::DELIVERY_METHOD_WAREHOUSE_TO_WAREHOUSE => Yii::t('model_cse_delivery', 'delivery method - warehouse to warehouse'),
        );
    }

    public function getDeliveryMethodLabel()
    {
        $labels = $this->getDeliveryMethodLabels();
        if (isset($labels[$this->delivery_method]))
            return $labels[$this->delivery_method];

        return '';
    }

    public function getAvailableDeliveryMethodLabels()
    {
        $labels = $this->getDeliveryMethodLabels();
		foreach ($labels as $key => $value) {
			if (!in_array($key, array(self::DELIVERY_METHOD_UP_DOORS, self::DELIVERY_METHOD_WITH_RETURN)))
				unset($labels[$key]);
		}
		return $labels;
    }

    public function getPayerLabels($usePersonal = true)
    {
        $labels = array(
            self::PAYER_CUSTOMER => Yii::t('model_cse_delivery', 'payer - customer'),
            //self::PAYER_RECEIVER => Yii::t('model_cse_delivery', 'payer - receiver'),
            //self::PAYER_SENDER => Yii::t('model_cse_delivery', 'payer - sender'),
        );

        if ($this->user_id && $usePersonal) {
            if ($this->user && $this->user->id = $this->user_id) {
                if ($this->user->organization)
                    $labels[self::PAYER_CUSTOMER] = $this->user->organization;
            } else {
                $user = User::model()->findByPk($this->user_id, array(
                    'select' => array('id', 'organization'),
                ));
                if ($user && $user->organization)
                    $labels[self::PAYER_CUSTOMER] = $user->organization;
            }
        }

        return $labels;
    }

    public function getPayerLabel($usePersonal = true)
    {
        $labels = $this->getPayerLabels($usePersonal);
        if (isset($labels[$this->payer]))
            return $labels[$this->payer];

        return '';
    }

    public function getPaymentMethodLabels()
    {
        return array(
            self::PAYMENT_METHOD_CASHLESS => Yii::t('model_cse_delivery', 'payment method - cashless'),
            self::PAYMENT_METHOD_CASH => Yii::t('model_cse_delivery', 'payment method - cash'),
        );
    }

    public function getAvailablePaymentMethodLabels()
    {
        $labels = $this->getPaymentMethodLabels();
        //if ($this->payer == self::PAYER_CUSTOMER) {
            unset($labels[self::PAYMENT_METHOD_CASH]);
        /*} else {
            unset($labels[self::PAYMENT_METHOD_CASHLESS]);
        }*/
        return $labels;
    }

    public function getPaymentMethodLabel()
    {
        $labels = $this->getPaymentMethodLabels();
        if (isset($labels[$this->payment_method]))
            return $labels[$this->payment_method];
        return '';
    }

    public function getAvailablePaymentMethodOptions()
    {
        $options = $this->getPaymentMethodLabels();
        $available = $this->getAvailablePaymentMethodLabels();
        foreach ($options as $key => &$data) {
            $data = array(
                'label' => $data,
                'disabled' => (isset($available[$key])) ? false : true,
            );
        }
        return $options;
    }

    public function getAvailableUrgencyLabels()
    {
        $data = array();
        $urgencies = CseUrgency::model()->active()->findAll();
        foreach ($urgencies as $urgency) {
            $data[$urgency->id] = $urgency->caption;
        }
        return $data;
    }

    public function getTakeTimeLabels()
    {
        $labels = array();
        for ($i = 0; $i < 24; $i++) {
            $labels[$i] = $i.':00';
        }
        return $labels;
    }

    public function defaultScope()
    {
        $tableAlias = $this->getTableAlias(false, false);
        return array(
            'order' => $tableAlias.'.timestamp DESC',
        );
    }

    public function byUserId($userId)
    {
        $tableAlias = $this->getTableAlias();

        $this->getDbCriteria()->mergeWith(array(
            'condition' => $tableAlias.'.user_id = :selected_user_id',
            'params' => array(
                ':selected_user_id' => $userId,
            ),
        ));

        return $this;
    }

    public function isWaybillAvailable()
    {
        if ($this->status == self::STATUS_SYNCED && $this->cse_id)
            return true;

        return false;
    }

    public function downloadWaybillApi()
    {
        $file = Yii::app()->cseApi->printDocument($this->cse_id);
        if ($file) {
            header("Content-type:application/pdf");
            header('Content-Disposition:attachment;filename='.$this->id.'_'.$this->cse_id.'.pdf');
            echo $file;
            return true;
        }

        return false;
    }

    public function downloadWaybillWeb()
    {
        $file = Yii::app()->cseApi->printDocumentWeb($this->cse_id);
        if ($file) {
            header("Content-type:application/pdf");
            header('Content-Disposition:attachment;filename='.$this->id.'_'.$this->cse_id.'.pdf');
            echo $file;
            return true;
        }

        return false;
    }

    public function isSynchronizeAvailable()
    {
        if ($this->status == self::STATUS_SYNC || $this->status == self::STATUS_SYNCED)
            return false;
        return true;
    }

    public function synchronize()
    {
        if ($this->status != self::STATUS_SYNC) {
            $attributes = array(
                'status' => self::STATUS_SYNC,
            );
            if ($this->client_status != self::CLIENT_STATUS_DELIVERED)
                $attributes['client_status'] = self::CLIENT_STATUS_IN_WORK;
            $this->saveAttributes($attributes);

            try {
                $data = Yii::app()->cseApi->saveOrder($this->getCseParams());

                $cseId = $this->getCseIdFromResponse($data);
                if ($cseId) {
                    $this->saveAttributes(array(
                        'status' => self::STATUS_SYNCED,
                        'cse_id' => $cseId,
                    ));
                    return true;
                } else {
                    $this->saveAttributes(array(
                        'status' => self::STATUS_NOT_SYNCED,
                    ));

                    $this->addCseErrors($data);
                }
            } catch (Exception $e) {
                $this->saveAttributes(array(
                    'status' => self::STATUS_NOT_SYNCED,
                ));
            }
        }

        return false;
    }

    public function search()
    {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria=new CDbCriteria;

        $tableAlias = $this->getTableAlias();

        $criteria->with = array(
            'senderCity' => array(
                'select' => array('id', 'caption'),
            ),
            'recipientCity' => array(
                'select' => array('id', 'caption'),
            )
        );

        $criteria->compare($tableAlias.'.user_id', $this->user_id);
        $criteria->compare($tableAlias.'.sender', $this->sender, true);
        $criteria->compare($tableAlias.'.recipient', $this->recipient, true);
        $criteria->compare($tableAlias.'.cargo_seats_number', $this->cargo_seats_number);
        $criteria->compare($tableAlias.'.cargo_weight', $this->cargo_weight, true);
        $criteria->compare($tableAlias.'.status', $this->status);
        $criteria->compare($tableAlias.'.client_status', $this->status);

        if ($this->timestamp) {
            $dates = explode('-', $this->timestamp);
            foreach ($dates as &$date) {
                $date = trim($date);
            }

            $timestamps = array();

            switch (count($dates)) {
                case 1:
                    $startTimestamp = CDateTimeParser::parse($dates[0].' 00:00:00','dd.MM.yyyy hh:mm:ss');
                    $endTimestamp = CDateTimeParser::parse($dates[0].' 23:59:59','dd.MM.yyyy hh:mm:ss');
                    if ($startTimestamp && $endTimestamp) {
                        $timestamps = array($startTimestamp, $endTimestamp);
                        sort($timestamps);
                    }
                    break;
                case 2:
                    $startTimestamp = CDateTimeParser::parse($dates[0].' 00:00:00','dd.MM.yyyy hh:mm:ss');
                    $endTimestamp = CDateTimeParser::parse($dates[1].' 00:00:00','dd.MM.yyyy hh:mm:ss');
                    if ($startTimestamp && $endTimestamp) {
                        $timestamps = array($startTimestamp, $endTimestamp);
                        sort($timestamps);
                        $timestamps[1] = $timestamps[1] + 24*60*60-1;
                    }
                    break;
            }

            if (!empty($timestamps)) {
                $criteria->addCondition($tableAlias.'.timestamp >= :start_timestamp AND '.$tableAlias.'.timestamp <= :end_timestamp');
                $criteria->params[':start_timestamp'] = $timestamps[0];
                $criteria->params[':end_timestamp'] = $timestamps[1];
            }
        }

        if ($this->sender_city) {
            $criteria->addCondition('senderCity.caption LIKE :sender_city_like');
            $criteria->params[':sender_city_like'] = $this->sender_city.'%';
        }

        if ($this->recipient_city) {
            $criteria->addCondition('recipientCity.caption LIKE :recipient_city_like');
            $criteria->params[':recipient_city_like'] = $this->recipient_city.'%';
        }

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }


}