<?php

/**
 * This is the model class for table "cse_delivery".
 *
 * The followings are the available columns in table 'cse_delivery':
 * @property string $id
 * @property string $user_id
 * @property string $timestamp
 * @property integer $status
 * @property integer $client_status
 * @property string $cse_id
 * @property double $price
 * @property string $sender
 * @property string $sender_contact
 * @property string $sender_city
 * @property string $sender_address
 * @property string $sender_post_index
 * @property string $sender_phone
 * @property string $sender_email
 * @property string $sender_info
 * @property string $recipient
 * @property string $recipient_contact
 * @property string $recipient_city
 * @property string $recipient_address
 * @property string $recipient_post_index
 * @property string $recipient_phone
 * @property string $recipient_email
 * @property string $recipient_info
 * @property double $customs_value
 * @property string $customs_currency
 * @property string $cargo_type
 * @property string $cargo_seats_number
 * @property double $cargo_weight
 * @property double $cargo_height
 * @property double $cargo_width
 * @property double $cargo_length
 * @property string $cargo_description
 * @property string $delivery_method
 * @property string $urgency_id
 * @property string $payer
 * @property string $payment_method
 * @property string $take_date
 * @property string $take_time_from
 * @property string $take_time_to
 * @property string $comment
 * @property double $insurance_rate
 * @property double $declared_value_rate
 * @property string $notify_phone
 * @property string $notify_email
 *
 * The followings are the available model relations:
 * @property CseUrgency $urgency
 * @property CseCity $senderCity
 * @property CseCity $recipientCity
 * @property User $user
 */
class BaseCseDelivery extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return BaseCseDelivery the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'cse_delivery';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id, timestamp, status, client_status, cse_id, sender, sender_city, sender_address, sender_phone, recipient, recipient_city, recipient_address, recipient_phone, customs_currency, cargo_type, cargo_seats_number, cargo_weight, delivery_method, urgency_id, payer, payment_method', 'required'),
			array('status, client_status', 'numerical', 'integerOnly'=>true),
			array('price, customs_value, cargo_weight, cargo_height, cargo_width, cargo_length, insurance_rate, declared_value_rate', 'numerical'),
			array('user_id, timestamp', 'length', 'max'=>11),
			array('cse_id, sender_post_index, recipient_post_index, notify_phone', 'length', 'max'=>20),
			array('sender, sender_contact, sender_phone, sender_email, recipient, recipient_contact, recipient_phone, recipient_email, notify_email', 'length', 'max'=>100),
			array('sender_city, recipient_city, customs_currency, cargo_type, urgency_id', 'length', 'max'=>40),
			array('sender_address, recipient_address', 'length', 'max'=>255),
			array('cargo_seats_number', 'length', 'max'=>10),
			array('delivery_method, payer, payment_method', 'length', 'max'=>5),
			array('take_time_from, take_time_to', 'length', 'max'=>2),
			array('sender_info, recipient_info, cargo_description, take_date, comment', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, user_id, timestamp, status, client_status, cse_id, price, sender, sender_contact, sender_city, sender_address, sender_post_index, sender_phone, sender_email, sender_info, recipient, recipient_contact, recipient_city, recipient_address, recipient_post_index, recipient_phone, recipient_email, recipient_info, customs_value, customs_currency, cargo_type, cargo_seats_number, cargo_weight, cargo_height, cargo_width, cargo_length, cargo_description, delivery_method, urgency_id, payer, payment_method, take_date, take_time_from, take_time_to, comment, insurance_rate, declared_value_rate, notify_phone, notify_email', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'urgency' => array(self::BELONGS_TO, 'CseUrgency', 'urgency_id'),
			'senderCity' => array(self::BELONGS_TO, 'CseCity', 'sender_city'),
			'recipientCity' => array(self::BELONGS_TO, 'CseCity', 'recipient_city'),
			'user' => array(self::BELONGS_TO, 'User', 'user_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
            'id' => Yii::t('model_cse_delivery', 'id'),
            'user_id' => Yii::t('model_cse_delivery', 'user_id'),
            'timestamp' => Yii::t('model_cse_delivery', 'timestamp'),
            'status' => Yii::t('model_cse_delivery', 'status'),
            'client_status' => Yii::t('model_cse_delivery', 'client_status'),
            'cse_id' => Yii::t('model_cse_delivery', 'cse_id'),
            'price' => Yii::t('model_cse_delivery', 'price'),
            'sender' => Yii::t('model_cse_delivery', 'sender'),
            'sender_contact' => Yii::t('model_cse_delivery', 'sender_contact'),
            'sender_city' => Yii::t('model_cse_delivery', 'sender_city'),
            'sender_address' => Yii::t('model_cse_delivery', 'sender_address'),
            'sender_post_index' => Yii::t('model_cse_delivery', 'sender_post_index'),
            'sender_phone' => Yii::t('model_cse_delivery', 'sender_phone'),
            'sender_email' => Yii::t('model_cse_delivery', 'sender_email'),
            'sender_info' => Yii::t('model_cse_delivery', 'sender_info'),
            'recipient' => Yii::t('model_cse_delivery', 'recipient'),
            'recipient_contact' => Yii::t('model_cse_delivery', 'recipient_contact'),
            'recipient_city' => Yii::t('model_cse_delivery', 'recipient_city'),
            'recipient_address' => Yii::t('model_cse_delivery', 'recipient_address'),
            'recipient_post_index' => Yii::t('model_cse_delivery', 'recipient_post_index'),
            'recipient_phone' => Yii::t('model_cse_delivery', 'recipient_phone'),
            'recipient_email' => Yii::t('model_cse_delivery', 'recipient_email'),
            'recipient_info' => Yii::t('model_cse_delivery', 'recipient_info'),
            'customs_value' => Yii::t('model_cse_delivery', 'customs_value'),
            'customs_currency' => Yii::t('model_cse_delivery', 'customs_currency'),
            'cargo_type' => Yii::t('model_cse_delivery', 'cargo_type'),
            'cargo_seats_number' => Yii::t('model_cse_delivery', 'cargo_seats_number'),
            'cargo_weight' => Yii::t('model_cse_delivery', 'cargo_weight'),
            'cargo_height' => Yii::t('model_cse_delivery', 'cargo_height'),
            'cargo_width' => Yii::t('model_cse_delivery', 'cargo_width'),
            'cargo_length' => Yii::t('model_cse_delivery', 'cargo_length'),
            'cargo_description' => Yii::t('model_cse_delivery', 'cargo_description'),
            'delivery_method' => Yii::t('model_cse_delivery', 'delivery_method'),
            'urgency_id' => Yii::t('model_cse_delivery', 'urgency_id'),
            'payer' => Yii::t('model_cse_delivery', 'payer'),
            'payment_method' => Yii::t('model_cse_delivery', 'payment_method'),
            'take_date' => Yii::t('model_cse_delivery', 'take_date'),
            'take_time_from' => Yii::t('model_cse_delivery', 'take_time_from'),
            'take_time_to' => Yii::t('model_cse_delivery', 'take_time_to'),
            'comment' => Yii::t('model_cse_delivery', 'comment'),
            'insurance_rate' => Yii::t('model_cse_delivery', 'insurance_rate'),
            'declared_value_rate' => Yii::t('model_cse_delivery', 'declared_value_rate'),
            'notify_phone' => Yii::t('model_cse_delivery', 'notify_phone'),
            'notify_email' => Yii::t('model_cse_delivery', 'notify_email'),
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id,true);
		$criteria->compare('user_id',$this->user_id,true);
		$criteria->compare('timestamp',$this->timestamp,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('client_status',$this->client_status);
		$criteria->compare('cse_id',$this->cse_id,true);
		$criteria->compare('price',$this->price);
		$criteria->compare('sender',$this->sender,true);
		$criteria->compare('sender_contact',$this->sender_contact,true);
		$criteria->compare('sender_city',$this->sender_city,true);
		$criteria->compare('sender_address',$this->sender_address,true);
		$criteria->compare('sender_post_index',$this->sender_post_index,true);
		$criteria->compare('sender_phone',$this->sender_phone,true);
		$criteria->compare('sender_email',$this->sender_email,true);
		$criteria->compare('sender_info',$this->sender_info,true);
		$criteria->compare('recipient',$this->recipient,true);
		$criteria->compare('recipient_contact',$this->recipient_contact,true);
		$criteria->compare('recipient_city',$this->recipient_city,true);
		$criteria->compare('recipient_address',$this->recipient_address,true);
		$criteria->compare('recipient_post_index',$this->recipient_post_index,true);
		$criteria->compare('recipient_phone',$this->recipient_phone,true);
		$criteria->compare('recipient_email',$this->recipient_email,true);
		$criteria->compare('recipient_info',$this->recipient_info,true);
		$criteria->compare('customs_value',$this->customs_value);
		$criteria->compare('customs_currency',$this->customs_currency,true);
		$criteria->compare('cargo_type',$this->cargo_type,true);
		$criteria->compare('cargo_seats_number',$this->cargo_seats_number,true);
		$criteria->compare('cargo_weight',$this->cargo_weight);
		$criteria->compare('cargo_height',$this->cargo_height);
		$criteria->compare('cargo_width',$this->cargo_width);
		$criteria->compare('cargo_length',$this->cargo_length);
		$criteria->compare('cargo_description',$this->cargo_description,true);
		$criteria->compare('delivery_method',$this->delivery_method,true);
		$criteria->compare('urgency_id',$this->urgency_id,true);
		$criteria->compare('payer',$this->payer,true);
		$criteria->compare('payment_method',$this->payment_method,true);
		$criteria->compare('take_date',$this->take_date,true);
		$criteria->compare('take_time_from',$this->take_time_from,true);
		$criteria->compare('take_time_to',$this->take_time_to,true);
		$criteria->compare('comment',$this->comment,true);
		$criteria->compare('insurance_rate',$this->insurance_rate);
		$criteria->compare('declared_value_rate',$this->declared_value_rate);
		$criteria->compare('notify_phone',$this->notify_phone,true);
		$criteria->compare('notify_email',$this->notify_email,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}