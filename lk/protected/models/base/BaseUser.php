<?php

/**
 * This is the model class for table "user".
 *
 * The followings are the available columns in table 'user':
 * @property string $id
 * @property string $email
 * @property string $display
 * @property string $password_hash
 * @property string $security_token
 * @property string $auth_key
 * @property integer $status
 * @property integer $type
 * @property integer $create_time
 * @property integer $last_login_time
 * @property string $inn
 * @property string $phone
 * @property string $organization
 * @property string $additional_email
 * @property double $debt
 * @property double $debt_limit
 * @property string $comment
 *
 * The followings are the available model relations:
 * @property Appeal[] $appeals
 * @property AppealMessage[] $appealMessages
 * @property CseAddressBook[] $cseAddressBooks
 * @property CseDelivery[] $cseDeliveries
 * @property PostDelivery[] $postDeliveries
 * @property AppealDepartament[] $appealDepartaments
 * @property BusinessCenter[] $businessCenters
 */
class BaseUser extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return BaseUser the static model class
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
		return 'user';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('email, display, password_hash, security_token, status, type, create_time, last_login_time, inn, phone, organization, additional_email, debt, debt_limit, comment', 'required'),
			array('status, type, create_time, last_login_time', 'numerical', 'integerOnly'=>true),
			array('debt, debt_limit', 'numerical'),
			array('email, phone, organization, additional_email', 'length', 'max'=>100),
			array('display', 'length', 'max'=>120),
			array('password_hash', 'length', 'max'=>64),
			array('security_token', 'length', 'max'=>50),
			array('auth_key', 'length', 'max'=>32),
			array('inn', 'length', 'max'=>20),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, email, display, password_hash, security_token, auth_key, status, type, create_time, last_login_time, inn, phone, organization, additional_email, debt, debt_limit, comment', 'safe', 'on'=>'search'),
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
			'appeals' => array(self::HAS_MANY, 'Appeal', 'user_id'),
			'appealMessages' => array(self::HAS_MANY, 'AppealMessage', 'user_id'),
			'cseAddressBooks' => array(self::HAS_MANY, 'CseAddressBook', 'user_id'),
			'cseDeliveries' => array(self::HAS_MANY, 'CseDelivery', 'user_id'),
			'postDeliveries' => array(self::HAS_MANY, 'PostDelivery', 'user_id'),
			'appealDepartaments' => array(self::MANY_MANY, 'AppealDepartament', 'user_to_appeal_departament(user_id, appeal_departament_id)'),
			'businessCenters' => array(self::MANY_MANY, 'BusinessCenter', 'user_to_business_center(user_id, business_center_id)'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
            'id' => Yii::t('model_user', 'id'),
            'email' => Yii::t('model_user', 'email'),
            'display' => Yii::t('model_user', 'display'),
            'password_hash' => Yii::t('model_user', 'password_hash'),
            'security_token' => Yii::t('model_user', 'security_token'),
            'auth_key' => Yii::t('model_user', 'auth_key'),
            'status' => Yii::t('model_user', 'status'),
            'type' => Yii::t('model_user', 'type'),
            'create_time' => Yii::t('model_user', 'create_time'),
            'last_login_time' => Yii::t('model_user', 'last_login_time'),
            'inn' => Yii::t('model_user', 'inn'),
            'phone' => Yii::t('model_user', 'phone'),
            'organization' => Yii::t('model_user', 'organization'),
            'additional_email' => Yii::t('model_user', 'additional_email'),
            'debt' => Yii::t('model_user', 'debt'),
            'debt_limit' => Yii::t('model_user', 'debt_limit'),
            'comment' => Yii::t('model_user', 'comment'),
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
		$criteria->compare('email',$this->email,true);
		$criteria->compare('display',$this->display,true);
		$criteria->compare('password_hash',$this->password_hash,true);
		$criteria->compare('security_token',$this->security_token,true);
		$criteria->compare('auth_key',$this->auth_key,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('type',$this->type);
		$criteria->compare('create_time',$this->create_time);
		$criteria->compare('last_login_time',$this->last_login_time);
		$criteria->compare('inn',$this->inn,true);
		$criteria->compare('phone',$this->phone,true);
		$criteria->compare('organization',$this->organization,true);
		$criteria->compare('additional_email',$this->additional_email,true);
		$criteria->compare('debt',$this->debt);
		$criteria->compare('debt_limit',$this->debt_limit);
		$criteria->compare('comment',$this->comment,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}