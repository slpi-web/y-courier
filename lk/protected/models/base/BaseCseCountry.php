<?php

/**
 * This is the model class for table "cse_country".
 *
 * The followings are the available columns in table 'cse_country':
 * @property string $id
 * @property string $caption
 * @property integer $status
 *
 * The followings are the available model relations:
 * @property CseCity[] $cseCities
 * @property CseRegion[] $cseRegions
 */
class BaseCseCountry extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return BaseCseCountry the static model class
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
		return 'cse_country';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id, caption, status', 'required'),
			array('status', 'numerical', 'integerOnly'=>true),
			array('id', 'length', 'max'=>40),
			array('caption', 'length', 'max'=>50),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, caption, status', 'safe', 'on'=>'search'),
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
			'cseCities' => array(self::HAS_MANY, 'CseCity', 'country_id'),
			'cseRegions' => array(self::HAS_MANY, 'CseRegion', 'country_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
            'id' => Yii::t('model_cse_country', 'id'),
            'caption' => Yii::t('model_cse_country', 'caption'),
            'status' => Yii::t('model_cse_country', 'status'),
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
		$criteria->compare('status',$this->status);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}