<?php

class BusinessCenter extends BaseBusinessCenter
{

    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public function rules()
    {
        return array(
        );
    }

    public function relations()
    {
        return array(
            'appeals' => array(self::HAS_MANY, 'Appeal', 'business_center_id'),
            'postDeliveries' => array(self::HAS_MANY, 'PostDelivery', 'business_center_id'),
            'users' => array(self::MANY_MANY, 'User', 'user_to_business_center(business_center_id, user_id)'),
        );
    }

    public function defaultScope()
    {
        $tableAlias = $this->getTableAlias(false, false);
        return array(
            'order' => $tableAlias.'.caption ASC',
        );
    }

    public function byUserId($userId)
    {
        $tableAlias = $this->getTableAlias();
        $criteria=new CDbCriteria;
        $criteria->join = 'LEFT JOIN user_to_business_center u2bc ON '.$tableAlias.'.id = u2bc.business_center_id';
        $criteria->condition = 'u2bc.user_id = :selected_user_id';
        $criteria->params = array(
            ':selected_user_id' => $userId,
        );

        $this->getDbCriteria()->mergeWith($criteria);

        return $this;
    }

    public function search()
    {
        $criteria=new CDbCriteria;

        $criteria->compare('caption', $this->caption, true);
        $criteria->compare('phone', $this->phone, true);
        $criteria->compare('address', $this->address, true);

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }

}