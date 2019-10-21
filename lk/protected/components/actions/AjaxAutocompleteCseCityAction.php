<?php

Yii::import('application.components.actions.BaseAjaxAction');

class AjaxAutocompleteCseCityAction extends BaseAjaxAction
{

    public function run($country_id, $region_id = null, $area_id = null, $page = 1, $path = false, $query = '')
    {
        $cacheKey = 'cse_region_ac/'.$country_id.'/'.$region_id.'/'.$area_id.'/'.$page.'/'.$query;
        $result = Yii::app()->cache->get($cacheKey);
        if (!$result) {
            $result = array();

            if ($path == 'true')
                $path = true;
            else
                $path = false;

            $dataProvider = new CActiveDataProvider('CseCity', array(
                'criteria' => CseCity::getFindCityCriteria($country_id, $region_id, $area_id, $query, $path),
                'pagination' => array(
                    'pageSize' => isset(Yii::app()->params['autocompletePageSize']) ? Yii::app()->params['autocompletePageSize'] : 10,
                    'currentPage' => $page,
                ),
            ));

            $result['total_count'] = $dataProvider->getTotalItemCount();
            $result['items'] = array();

            $cities = $dataProvider->getData();
            if (is_array($cities)) {
                foreach ($cities as $city) {
                    $caption = $city->caption;
                    if ($path) {
                        if ($city->area)
                            $caption .= ', ' . $city->area->caption;
                        if ($city->region)
                            $caption .= ', ' . $city->region->caption;
                    }
                    $result['items'][] = array(
                        'id' => $city->id,
                        'text' => $caption,
                    );
                }
            }

            if (mb_strlen($query) < 3)
                Yii::app()->cache->set($cacheKey, $result, $this->cacheDays*24*60*60);
        }

        $this->response($result);
    }

}