<?php

Yii::import('application.components.actions.BaseAjaxAction');

class AjaxAutocompleteCseRegionAction extends BaseAjaxAction
{

    public function run($country_id, $page = 1, $query = '')
    {
        $cacheKey = 'cse_region_ac/'.$country_id.'/'.$page.'/'.$query;
        $result = Yii::app()->cache->get($cacheKey);
        if (!$result) {
            $result = array();

            $dataProvider = new CActiveDataProvider('CseRegion', array(
                'criteria' => CseRegion::getFindCriteria($country_id, $query),
                'pagination' => array(
                    'pageSize' => isset(Yii::app()->params['autocompletePageSize']) ? Yii::app()->params['autocompletePageSize'] : 10,
                    'currentPage' => $page,
                ),
            ));

            $result['total_count'] = $dataProvider->getTotalItemCount();
            $result['items'] = array();

            $regions = $dataProvider->getData();
            if (is_array($regions)) {
                foreach ($regions as $region) {
                    $result['items'][] = array(
                        'id' => $region->id,
                        'text' => $region->caption,
                    );
                }
            }

            if (mb_strlen($query) < 3)
                Yii::app()->cache->set($cacheKey, $result, $this->cacheDays*24*60*60);
        }

        $this->response($result);
    }

}