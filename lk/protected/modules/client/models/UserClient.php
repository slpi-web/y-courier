<?php

/**
 * Created by PhpStorm.
 * User: dim
 * Date: 27.11.2015
 * Time: 7:44
 */
class UserClient extends User
{

    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public function rules()
    {
        return array(
            array('additional_email', 'email', 'allowEmpty' => true),
        );
    }

    public function afterFind()
    {
        parent::afterFind();
        $this->loadRelations();
    }

    public function loadRelations()
    {
        switch ($this->type) {
            case self::TYPE_CLIENT:
                $this->business_centers = $this->getBusinessCentersId();
                break;
            case self::TYPE_WORKER:
                $this->appeal_departaments = $this->getAppealDepartamentsId();
                break;
        }
    }

}