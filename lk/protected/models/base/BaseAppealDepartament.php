<?php

/**
 * This is the model class for table "appeal_departament".
 *
 * The followings are the available columns in table 'appeal_departament':
 * @property string $id
 * @property integer $status
 * @property string $caption
 * @property string $email_list
 *
 * The followings are the available model relations:
 * @property AppealToAppealDepartament[] $appealToAppealDepartaments
 * @property User[] $users
 */
class BaseAppealDepartament extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return BaseAppealDepartament the static model class
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
		return 'appeal_departament';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('status, caption, email_list', 'required'),
			array('status', 'numerical', 'integerOnly'=>true),
			array('caption', 'length', 'max'=>100),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, status, caption, email_list', 'safe', 'on'=>'search'),
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
			'appealToAppealDepartaments' => array(self::HAS_MANY, 'AppealToAppealDepartament', 'appeal_departament_id'),
			'users' => array(self::MANY_MANY, 'User', 'user_to_appeal_departament(appeal_departament_id, user_id)'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
            'id' => Yii::t('model_appeal_departament', 'id'),
            'status' => Yii::t('model_appeal_departament', 'status'),
            'caption' => Yii::t('model_appeal_departament', 'caption'),
            'email_list' => Yii::t('model_appeal_departament', 'email_list'),
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
		$criteria->compare('status',$this->status);
		$criteria->compare('caption',$this->caption,true);
		$criteria->compare('email_list',$this->email_list,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}