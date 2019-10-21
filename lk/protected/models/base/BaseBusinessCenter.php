<?php

/**
 * This is the model class for table "business_center".
 *
 * The followings are the available columns in table 'business_center':
 * @property string $id
 * @property string $caption
 * @property string $phone
 * @property string $address
 *
 * The followings are the available model relations:
 * @property Appeal[] $appeals
 * @property PostDelivery[] $postDeliveries
 * @property User[] $users
 */
class BaseBusinessCenter extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return BaseBusinessCenter the static model class
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
		return 'business_center';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('caption, phone, address', 'required'),
			array('caption, address', 'length', 'max'=>255),
			array('phone', 'length', 'max'=>100),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, caption, phone, address', 'safe', 'on'=>'search'),
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
			'appeals' => array(self::HAS_MANY, 'Appeal', 'business_center_id'),
			'postDeliveries' => array(self::HAS_MANY, 'PostDelivery', 'business_center_id'),
			'users' => array(self::MANY_MANY, 'User', 'user_to_business_center(business_center_id, user_id)'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
            'id' => Yii::t('model_business_center', 'id'),
            'caption' => Yii::t('model_business_center', 'caption'),
            'phone' => Yii::t('model_business_center', 'phone'),
            'address' => Yii::t('model_business_center', 'address'),
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
		$criteria->compare('caption',$this->caption,true);
		$criteria->compare('phone',$this->phone,true);
		$criteria->compare('address',$this->address,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}