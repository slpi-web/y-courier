<?php

/**
 * This is the model class for table "cse_city".
 *
 * The followings are the available columns in table 'cse_city':
 * @property string $id
 * @property string $country_id
 * @property string $region_id
 * @property string $parent_city_id
 * @property integer $type
 * @property string $caption
 * @property integer $status
 *
 * The followings are the available model relations:
 * @property CseAddressBook[] $cseAddressBooks
 * @property CseCountry $country
 * @property CseRegion $region
 * @property BaseCseCity $parentCity
 * @property BaseCseCity[] $cseCities
 * @property CseDelivery[] $cseDeliveries
 * @property CseDelivery[] $cseDeliveries1
 */
class BaseCseCity extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return BaseCseCity the static model class
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
		return 'cse_city';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id, country_id, type, caption, status', 'required'),
			array('type, status', 'numerical', 'integerOnly'=>true),
			array('id, country_id, region_id, parent_city_id', 'length', 'max'=>40),
			array('caption', 'length', 'max'=>100),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, country_id, region_id, parent_city_id, type, caption, status', 'safe', 'on'=>'search'),
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
			'cseAddressBooks' => array(self::HAS_MANY, 'CseAddressBook', 'city'),
			'country' => array(self::BELONGS_TO, 'CseCountry', 'country_id'),
			'region' => array(self::BELONGS_TO, 'CseRegion', 'region_id'),
			'parentCity' => array(self::BELONGS_TO, 'BaseCseCity', 'parent_city_id'),
			'cseCities' => array(self::HAS_MANY, 'BaseCseCity', 'parent_city_id'),
			'cseDeliveries' => array(self::HAS_MANY, 'CseDelivery', 'sender_city'),
			'cseDeliveries1' => array(self::HAS_MANY, 'CseDelivery', 'recipient_city'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
            'id' => Yii::t('model_cse_city', 'id'),
            'country_id' => Yii::t('model_cse_city', 'country_id'),
            'region_id' => Yii::t('model_cse_city', 'region_id'),
            'parent_city_id' => Yii::t('model_cse_city', 'parent_city_id'),
            'type' => Yii::t('model_cse_city', 'type'),
            'caption' => Yii::t('model_cse_city', 'caption'),
            'status' => Yii::t('model_cse_city', 'status'),
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
		$criteria->compare('country_id',$this->country_id,true);
		$criteria->compare('region_id',$this->region_id,true);
		$criteria->compare('parent_city_id',$this->parent_city_id,true);
		$criteria->compare('type',$this->type);
		$criteria->compare('caption',$this->caption,true);
		$criteria->compare('status',$this->status);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}