<?php

Yii::import('application.components.actions.BaseAjaxAction');

class AjaxGetCseCountyInfoAction extends BaseAjaxAction
{

    public function run($country_id)
    {
        $result = Yii::app()->cache->get('country_info-'.$country_id);
        if (!$result) {
            $country = CseCountry::model()->active()->findByPk($country_id);
            if ($country) {
                $result = array(
                    'regions' => ($country->getRegionsCount() > 0) ? true : false ,
                    'areas' => ($country->getAreasCount() > 0 ) ? true : false,
                );
                Yii::app()->cache->set('country_info-'.$country_id, $result, $this->cacheDays*24*60*60);
            }
        }

        $this->response($result);
    }

}