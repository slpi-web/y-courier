<?php

class AppealWorker extends Appeal
{

    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public function rules()
    {
        return array(
            array('user_id, business_center_id, appeal_departaments, status, subject, text', 'required', 'on' => 'add'),
            array('status', 'in', 'range' => array_keys($this->getAvailableStatusLabels())),
            array('user_id, business_center_id', 'length', 'max'=>11, 'on' => 'add'),
            array('subject', 'length', 'max'=>255 , 'on' => 'add'),
            array('appeal_departaments', 'ArrayValidator', 'allowEmpty' => false, 'validator' => 'numerical', 'on' => 'add'),

            array('id, user_id, appeal_departaments, business_center_id, timestamp, status, subject', 'safe', 'on'=>'search'),
        );
    }

    public function workerAccessable($userId = null)
    {
        if ($userId)
            $user = User::model()->findByPk($userId);
        else
            $user = Yii::app()->user->loadUser();

        $tableAlias = $this->getTableAlias();

        $appealDepartaments = array(-1);
        if ($user) {
            $appealDepartaments = $user->getAppealDepartamentsId();
        }

        $criteria = new CDbCriteria();
        if ($appealDepartaments)
            $appealDepartaments = implode(',', $appealDepartaments);
        else
            $appealDepartaments = (int) $this->appeal_departaments;

        $criteria->addCondition("{$tableAlias}.id IN (SELECT appeal_id FROM `appeal_to_appeal_departament` WHERE appeal_departament_id IN ({$appealDepartaments}))");

        $this->getDbCriteria()->mergeWith($criteria);

        return $this;
    }

    public function getAvailableStatusLabels()
    {
        $labels = $this->getStatusLabels();
        unset($labels[self::STATUS_CLOSED]);
        return $labels;
    }

    public function search()
    {
        $criteria = $this->getSearchCriteria();

        $criteria->scopes = array('workerAccessable');

        $criteria->with = array(
            'user' => array(
                'select' => array('id', 'display'),
            ),
            'businessCenter' => array(
                'select' => array('id', 'caption'),
            ),
            'appealDepartaments' => array(
                'select' => array('id', 'caption'),
            ),
        );

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }

}