<?php

Yii::import('application.components.actions.BaseAjaxAction');

class AjaxAutocompleteCseAreaAction extends BaseAjaxAction
{

    public function run($country_id, $region_id = false, $page = 1, $query = '')
    {
        $cacheKey = 'cse_region_ac/'.$country_id.'/'.$region_id.'/'.$page.'/'.$query;
        $result = Yii::app()->cache->get($cacheKey);
        if (!$result) {
            $result = array();

            $dataProvider = new CActiveDataProvider('CseCity', array(
                'criteria' => CseCity::getFindAreaCriteria($country_id, $region_id, $query),
                'pagination' => array(
                    'pageSize' => isset(Yii::app()->params['autocompletePageSize']) ? Yii::app()->params['autocompletePageSize'] : 10,
                    'currentPage' => $page,
                ),
            ));

            $result['total_count'] = $dataProvider->getTotalItemCount();
            $result['items'] = array();

            $areas = $dataProvider->getData();
            if (is_array($areas)) {
                foreach ($areas as $area) {
                    $result['items'][] = array(
                        'id' => $area->id,
                        'text' => $area->caption,
                    );
                }
            }

            if (mb_strlen($query) < 3)
                Yii::app()->cache->set($cacheKey, $result, $this->cacheDays*24*60*60);
        }

        $this->response($result);
    }

}