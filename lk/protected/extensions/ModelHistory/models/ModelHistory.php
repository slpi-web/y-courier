<?php

class ModelHistory extends BaseModelHistory
{

    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public function primaryKey(){
        return array('model', 'id', 'change_id');
    }

    public function rules()
    {
        return array(
            array('user_id, timestamp, fields', 'safe', 'on' => 'search'),
        );
    }

    public function relations()
    {
        return array(
            'user' => array(self::BELONGS_TO, 'User', 'user_id'),
        );
    }

    protected $searchLabels = false;

    protected function _getLastChangeId()
    {
        $last = Yii::app()->db->createCommand()
            ->select('MAX(change_id)')
            ->from($this->tableName())
            ->where('model = :model AND id = :id', array(
                ':model' => $this->model,
                ':id' => $this->id
            ))
            ->queryScalar();
        if (!$last)
            $last = 0;
        return $last;
    }

    protected function beforeSave()
    {
        if(parent::beforeSave()) {

            if ($this->isNewRecord) {
                $lastId = $this->_getLastChangeId();

                Yii::log('lastId '.print_r($lastId, true));

                $this->timestamp = time();
                $this->change_id = ($lastId + 1);
            }

            return true;
        }
        return false;
    }

    public function defaultScope()
    {
        $tableAlias = $this->getTableAlias(false, false);
        return array(
            'order' => $tableAlias.'.timestamp DESC',
        );
    }

    public function setFields($fields)
    {
        $this->fields = serialize($fields);
    }

    public function getFields()
    {
        return unserialize($this->fields);
    }

    public function setFieldsLabels($data)
    {
        if (is_array($data))
            $this->searchLabels = $data;
    }

    public function getFieldsLabels()
    {
        return $this->searchLabels;
    }

    public function search()
    {
        $tableAlias = $this->getTableAlias();

        $criteria=new CDbCriteria;

        $criteria->compare($tableAlias.'.model', $this->model);
        $criteria->compare($tableAlias.'.id', $this->id);

        if ($this->timestamp) {
            $dates = explode('-', $this->timestamp);
            foreach ($dates as &$date) {
                $date = trim($date);
            }

            $timestamps = array();

            switch (count($dates)) {
                case 1:
                    $startTimestamp = CDateTimeParser::parse($dates[0].' 00:00:00','dd.MM.yyyy hh:mm:ss');
                    $endTimestamp = CDateTimeParser::parse($dates[0].' 23:59:59','dd.MM.yyyy hh:mm:ss');
                    if ($startTimestamp && $endTimestamp) {
                        $timestamps = array($startTimestamp, $endTimestamp);
                        sort($timestamps);
                    }
                    break;
                case 2:
                    $startTimestamp = CDateTimeParser::parse($dates[0].' 00:00:00','dd.MM.yyyy hh:mm:ss');
                    $endTimestamp = CDateTimeParser::parse($dates[1].' 00:00:00','dd.MM.yyyy hh:mm:ss');
                    if ($startTimestamp && $endTimestamp) {
                        $timestamps = array($startTimestamp, $endTimestamp);
                        sort($timestamps);
                        $timestamps[1] = $timestamps[1] + 24*60*60-1;
                    }
                    break;
            }

            if (!empty($timestamps)) {
                $criteria->addCondition($tableAlias.'.timestamp >= :start_timestamp AND '.$tableAlias.'.timestamp <= :end_timestamp');
                $criteria->params[':start_timestamp'] = $timestamps[0];
                $criteria->params[':end_timestamp'] = $timestamps[1];
            }
        }

        $criteria->compare($tableAlias.'.user_id', $this->user_id);
        if ($this->fields)
            $criteria->compare($tableAlias.'.fields', serialize($this->fields), true);

        $criteria->with = array(
            'user' => array(
                'select' => array('id', 'display'),
            ),
        );

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

}