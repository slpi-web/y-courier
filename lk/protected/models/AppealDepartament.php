<?php

class AppealDepartament extends BaseAppealDepartament
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
            'appealToAppealDepartaments' => array(self::HAS_MANY, 'AppealToAppealDepartament', 'appeal_departament_id'),
            'users' => array(self::MANY_MANY, 'User', 'user_to_appeal_departament(appeal_departament_id, user_id)'),
        );
    }

    public function getStatusLabels()
    {
        return StatusHelper::getLabels();
    }

    public function getStatusLabel()
    {
        return StatusHelper::getLabel($this->status);
    }

    public function scopes()
    {
        $tableAlias = $this->getTableAlias();

        return array(
            'active' => array(
                'condition' => $tableAlias.'.status = :status_active',
                'params' => array(
                    ':status_active' => StatusHelper::STATUS_ENABLED,
                ),
            ),
        );
    }

    public function defaultScope()
    {
        $tableAlias = $this->getTableAlias(false, false);
        return array(
            'order' => $tableAlias.'.caption ASC',
        );
    }

    public function search()
    {
        $criteria=new CDbCriteria;

        $criteria->compare('status',$this->status);
        $criteria->compare('caption',$this->caption,true);

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }

    public function getEmails()
    {
        return DataParser::parseEmailList($this->email_list);
    }

}