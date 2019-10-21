<?php

/**
 * This is the model class for table "appeal".
 *
 * The followings are the available columns in table 'appeal':
 * @property string $id
 * @property string $user_id
 * @property string $timestamp
 * @property string $business_center_id
 * @property integer $status
 * @property string $subject
 * @property string $text
 *
 * The followings are the available model relations:
 * @property User $user
 * @property BusinessCenter $businessCenter
 * @property AppealMessage[] $appealMessages
 * @property AppealDepartament[] $appealDepartaments
 */
class BaseAppeal extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return BaseAppeal the static model class
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
		return 'appeal';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id, timestamp, business_center_id, status, subject, text', 'required'),
			array('status', 'numerical', 'integerOnly'=>true),
			array('user_id, timestamp, business_center_id', 'length', 'max'=>11),
			array('subject', 'length', 'max'=>255),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, user_id, timestamp, business_center_id, status, subject, text', 'safe', 'on'=>'search'),
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
			'appealMessages' => array(self::HAS_MANY, 'AppealMessage', 'appeal_id'),
			'appealDepartaments' => array(self::MANY_MANY, 'AppealDepartament', 'appeal_to_appeal_departament(appeal_id, appeal_departament_id)'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
            'id' => Yii::t('model_appeal', 'id'),
            'user_id' => Yii::t('model_appeal', 'user_id'),
            'timestamp' => Yii::t('model_appeal', 'timestamp'),
            'business_center_id' => Yii::t('model_appeal', 'business_center_id'),
            'status' => Yii::t('model_appeal', 'status'),
            'subject' => Yii::t('model_appeal', 'subject'),
            'text' => Yii::t('model_appeal', 'text'),
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
		$criteria->compare('business_center_id',$this->business_center_id,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('subject',$this->subject,true);
		$criteria->compare('text',$this->text,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}