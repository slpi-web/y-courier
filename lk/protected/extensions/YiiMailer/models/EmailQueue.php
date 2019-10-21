<?php
/**
 * Created by PhpStorm.
 * User: dim
 * Date: 04.04.14
 * Time: 17:23
 */


class EmailQueue extends BaseEmailQueue
{

    const STATUS_NONE = 0;
    const STATUS_SENT = 1;
    const STATUS_ERROR = 2;

    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public function rules()
    {
        return array();
    }

    public function first($count = 1)
    {
        $this->getDbCriteria()->mergeWith(array(
            'order' => $this->getTableAlias().'.timestamp ASC',
            'limit' => $count,
        ));

        return $this;
    }

    public function scopes()
    {
        return array(
            'queued' => array(
                'condition' => $this->getTableAlias().'.status = :status_none',
                'params' => array(
                    ':status_none' => self::STATUS_NONE,
                ),
            ),
        );
    }

    public function beforeSave()
    {
        if (parent::beforeSave()) {
            if ($this->isNewRecord) {
                $this->timestamp = new CDbExpression('NOW()');
                $this->status = self::STATUS_NONE;
            }

            return true;
        }
        return false;
    }

} 