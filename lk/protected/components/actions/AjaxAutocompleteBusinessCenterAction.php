<?php

Yii::import('application.components.actions.BaseAjaxAction');

class AjaxAutocompleteBusinessCenterAction extends BaseAjaxAction
{
    public $scopes = array();

    public function run($page = 1, $query = '')
    {
        $cacheKey = 'business_center_ac/'.$page.'/'.$query;
        $result = Yii::app()->cache->get($cacheKey);
        if (!$result) {
            $result = array();

            $criteria = new CDbCriteria();
            $criteria->select = 'id, caption';
            $criteria->scopes = $this->scopes;
            if ($query) {
                $criteria->addCondition('caption LIKE :caption_like');
                $criteria->params[':caption_like'] = '%'.$query.'%';
            }

            $dataProvider = new CActiveDataProvider('BusinessCenter', array(
                'criteria' => $criteria,
                'pagination' => array(
                    'pageSize' => isset(Yii::app()->params['autocompletePageSize']) ? Yii::app()->params['autocompletePageSize'] : 10,
                    'currentPage' => $page,
                ),
            ));

            $result['total_count'] = $dataProvider->getTotalItemCount();
            $result['items'] = array();

            $businessCenters = $dataProvider->getData();
            if (is_array($businessCenters)) {
                foreach ($businessCenters as $businessCenter) {
                    $result['items'][] = array(
                        'id' => $businessCenter->id,
                        'text' => $businessCenter->caption,
                    );
                }
            }

            if (mb_strlen($query) < 3)
                Yii::app()->cache->set($cacheKey, $result, $this->cacheDays*24*60*60);
        }

        $this->response($result);
    }

}