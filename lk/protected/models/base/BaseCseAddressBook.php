<?php

/**
 * This is the model class for table "cse_address_book".
 *
 * The followings are the available columns in table 'cse_address_book':
 * @property string $id
 * @property string $user_id
 * @property integer $type
 * @property string $name
 * @property string $contact
 * @property string $city
 * @property string $address
 * @property string $post_index
 * @property string $phone
 * @property string $email
 * @property string $info
 *
 * The followings are the available model relations:
 * @property User $user
 * @property CseCity $city0
 */
class BaseCseAddressBook extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return BaseCseAddressBook the static model class
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
		return 'cse_address_book';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id, type, name, city, address, phone, info', 'required'),
			array('type', 'numerical', 'integerOnly'=>true),
			array('user_id', 'length', 'max'=>11),
			array('name, contact, phone, email', 'length', 'max'=>100),
			array('city', 'length', 'max'=>40),
			array('address', 'length', 'max'=>255),
			array('post_index', 'length', 'max'=>20),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, user_id, type, name, contact, city, address, post_index, phone, email, info', 'safe', 'on'=>'search'),
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
			'user' => array(self::BELONGS_TO, 'User', 'user_id'),
			'city0' => array(self::BELONGS_TO, 'CseCity', 'city'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
            'id' => Yii::t('model_cse_address_book', 'id'),
            'user_id' => Yii::t('model_cse_address_book', 'user_id'),
            'type' => Yii::t('model_cse_address_book', 'type'),
            'name' => Yii::t('model_cse_address_book', 'name'),
            'contact' => Yii::t('model_cse_address_book', 'contact'),
            'city' => Yii::t('model_cse_address_book', 'city'),
            'address' => Yii::t('model_cse_address_book', 'address'),
            'post_index' => Yii::t('model_cse_address_book', 'post_index'),
            'phone' => Yii::t('model_cse_address_book', 'phone'),
            'email' => Yii::t('model_cse_address_book', 'email'),
            'info' => Yii::t('model_cse_address_book', 'info'),
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
		$criteria->compare('type',$this->type);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('contact',$this->contact,true);
		$criteria->compare('city',$this->city,true);
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