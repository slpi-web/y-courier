<?php

/**
 * This is the model class for table "appeal_message".
 *
 * The followings are the available columns in table 'appeal_message':
 * @property string $id
 * @property string $appeal_id
 * @property string $user_id
 * @property string $timestamp
 * @property string $message
 *
 * The followings are the available model relations:
 * @property Appeal $appeal
 * @property User $user
 */
class BaseAppealMessage extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return BaseAppealMessage the static model class
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
		return 'appeal_message';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('appeal_id, timestamp, message', 'required'),
			array('appeal_id, user_id, timestamp', 'length', 'max'=>11),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, appeal_id, user_id, timestamp, message', 'safe', 'on'=>'search'),
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
			'appeal' => array(self::BELONGS_TO, 'Appeal', 'appeal_id'),
			'user' => array(self::BELONGS_TO, 'User', 'user_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
            'id' => Yii::t('model_appeal_message', 'id'),
            'appeal_id' => Yii::t('model_appeal_message', 'appeal_id'),
            'user_id' => Yii::t('model_appeal_message', 'user_id'),
            'timestamp' => Yii::t('model_appeal_message', 'timestamp'),
            'message' => Yii::t('model_appeal_message', 'message'),
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
		$criteria->compare('appeal_id',$this->appeal_id,true);
		$criteria->compare('user_id',$this->user_id,true);
		$criteria->compare('timestamp',$this->timestamp,true);
		$criteria->compare('message',$this->message,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}