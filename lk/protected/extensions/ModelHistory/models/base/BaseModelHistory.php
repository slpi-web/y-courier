<?php

/**
 * This is the model class for table "model_history".
 *
 * The followings are the available columns in table 'model_history':
 * @property string $model
 * @property string $id
 * @property string $change_id
 * @property string $timestamp
 * @property string $user_id
 * @property string $fields
 *
 * The followings are the available model relations:
 * @property User $user
 */
class BaseModelHistory extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return BaseModelHistory the static model class
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
		return 'model_history';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('model, id, change_id, timestamp, fields', 'required'),
			array('model, id', 'length', 'max'=>50),
			array('change_id, timestamp, user_id', 'length', 'max'=>11),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('model, id, change_id, timestamp, user_id, fields', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
            'model' => Yii::t('model_model_history', 'model'),
            'id' => Yii::t('model_model_history', 'id'),
            'change_id' => Yii::t('model_model_history', 'change_id'),
            'timestamp' => Yii::t('model_model_history', 'timestamp'),
            'user_id' => Yii::t('model_model_history', 'user_id'),
            'fields' => Yii::t('model_model_history', 'fields'),
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

		$criteria->compare('model',$this->model,true);
		$criteria->compare('id',$this->id,true);
		$criteria->compare('change_id',$this->change_id,true);
		$criteria->compare('timestamp',$this->timestamp,true);
		$criteria->compare('user_id',$this->user_id,true);
		$criteria->compare('fields',$this->fields,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}