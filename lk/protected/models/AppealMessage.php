<?php

class AppealMessage extends BaseAppealMessage
{

    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public function rules()
    {
        return array(
            array('message', 'required'),
            array('message', 'length', 'min'=>5),

            array('id, appeal_id, user_id, timestamp, message', 'safe', 'on'=>'search'),
        );
    }

    public function relations()
    {
        return array(
            'appeal' => array(self::BELONGS_TO, 'Appeal', 'appeal_id'),
            'user' => array(self::BELONGS_TO, 'User', 'user_id'),
        );
    }

    public function defaultScope()
    {
        $tableAlias = $this->getTableAlias(false, false);
        return array(
            'order' => $tableAlias.'.timestamp ASC',
        );
    }

    public function byAppealId($appealId)
    {
        $tableAlias = $this->getTableAlias();

        $this->getDbCriteria()->mergeWith(array(
            'condition' => $tableAlias.'.appeal_id = :selected_appeal_id',
            'params' => array(
                ':selected_appeal_id' => $appealId,
            ),
        ));

        return $this;
    }

    protected function beforeSave()
    {
        if(parent::beforeSave()) {

            if ($this->isNewRecord)
                $this->timestamp = time();

            return true;
        }
        return false;
    }

}