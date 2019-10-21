<?php

/**
 * This is the model class for table "post_delivery".
 *
 * The followings are the available columns in table 'post_delivery':
 * @property string $id
 * @property string $user_id
 * @property integer $status
 * @property string $timestamp
 * @property string $organization
 * @property string $business_center_id
 * @property string $office
 * @property integer $letters_count
 * @property string $comment
 *
 * The followings are the available model relations:
 * @property User $user
 * @property BusinessCenter $businessCenter
 */
class BasePostDelivery extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return BasePostDelivery the static model class
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
		return 'post_delivery';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id, status, timestamp, organization, business_center_id, office, letters_count, comment', 'required'),
			array('status, letters_count', 'numerical', 'integerOnly'=>true),
			array('user_id, timestamp, business_center_id', 'length', 'max'=>11),
			array('organization', 'length', 'max'=>100),
			array('office', 'length', 'max'=>50),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, user_id, status, timestamp, organization, business_center_id, office, letters_count, comment', 'safe', 'on'=>'search'),
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
			'businessCenter' => array(self::BELONGS_TO, 'BusinessCenter', 'business_center_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
            'id' => Yii::t('model_post_delivery', 'id'),
            'user_id' => Yii::t('model_post_delivery', 'user_id'),
            'status' => Yii::t('model_post_delivery', 'status'),
            'timestamp' => Yii::t('model_post_delivery', 'timestamp'),
            'organization' => Yii::t('model_post_delivery', 'organization'),
            'business_center_id' => Yii::t('model_post_delivery', 'business_center_id'),
            'office' => Yii::t('model_post_delivery', 'office'),
            'letters_count' => Yii::t('model_post_delivery', 'letters_count'),
            'comment' => Yii::t('model_post_delivery', 'comment'),
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
		$criteria->compare('status',$this->status);
		$criteria->compare('timestamp',$this->timestamp,true);
		$criteria->compare('organization',$this->organization,true);
		$criteria->compare('business_center_id',$this->business_center_id,true);
		$criteria->compare('office',$this->office,true);
		$criteria->compare('letters_count',$this->letters_count);
		$criteria->compare('comment',$this->comment,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}