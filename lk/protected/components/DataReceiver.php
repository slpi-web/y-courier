<?php

class DataReceiver {

    public static function getDisplayUserName($user) {
        if ($user instanceof User) {
            return $user->display;
        }
        return '';
    }

    public static function getBusinessCenterList($criteria = null)
    {
        if (is_array($criteria))
            $criteria = new CDbCriteria($criteria);

        if (!($criteria instanceof CDbCriteria)) {
            $criteria = new CDbCriteria();
            $criteria->scopes = array();
        }

        if ($criteria instanceof CDbCriteria) {
            $criteria->select = 'id, caption';
            $models = BusinessCenter::model()->findAll($criteria);
            if (is_array($models)) {
                $data = array();
                foreach ($models as $bc)
                    $data[$bc->id] = $bc->caption;

                return $data;
            }
        }

        return null;
    }

    public static function getAppealDepartamentsList($criteria = null)
    {
        if (is_array($criteria))
            $criteria = new CDbCriteria($criteria);

        if (!($criteria instanceof CDbCriteria)) {
            $criteria = new CDbCriteria();
            $criteria->scopes = array('active');
        }

        if ($criteria instanceof CDbCriteria) {
            $criteria->select = 'id, caption';
            $models = AppealDepartament::model()->findAll($criteria);
            if (is_array($models)) {
                $data = array();
                foreach ($models as $ad)
                    $data[$ad->id] = $ad->caption;

                return $data;
            }
        }

        return null;
    }

    public static function getCseCountryList($criteria = null)
    {
        if (is_array($criteria))
            $criteria = new CDbCriteria($criteria);

        if (!($criteria instanceof CDbCriteria)) {
            $criteria = new CDbCriteria();
            $criteria->scopes = array('active');
        }

        if ($criteria instanceof CDbCriteria) {
            $criteria->select = 'id, caption';
            $models = CseCountry::model()->findAll($criteria);
            if (is_array($models)) {
                $data = array();
                foreach ($models as $ad)
                    $data[$ad->id] = $ad->caption;

                return $data;
            }
        }

        return null;
    }



} 