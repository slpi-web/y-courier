<?php

Yii::import('application.components.actions.BaseAjaxAction');

class AjaxAutocompleteUserAction extends BaseAjaxAction
{
    public $scopes = array();

    public function run($page = 1, $query = '')
    {
        $cacheKey = 'user_ac/'.$page.'/'.$query;
        $result = Yii::app()->cache->get($cacheKey);
        if (!$result) {
            $result = array();

            $criteria = new CDbCriteria();
            $criteria->select = 'id, display';
            $criteria->scopes = $this->scopes;
            if ($query) {
                $criteria->addCondition('display LIKE :display_like');
                $criteria->params[':display_like'] = '%'.$query.'%';
            }

            $dataProvider = new CActiveDataProvider('User', array(
                'criteria' => $criteria,
                'pagination' => array(
                    'pageSize' => isset(Yii::app()->params['autocompletePageSize']) ? Yii::app()->params['autocompletePageSize'] : 10,
                    'currentPage' => $page,
                ),
            ));

            $result['total_count'] = $dataProvider->getTotalItemCount();
            $result['items'] = array();

            $users = $dataProvider->getData();
            if (is_array($users)) {
                foreach ($users as $user) {
                    $result['items'][] = array(
                        'id' => $user->id,
                        'text' => $user->display,
                    );
                }
            }

            if (mb_strlen($query) < 3)
                Yii::app()->cache->set($cacheKey, $result, $this->cacheDays*24*60*60);
        }

        $this->response($result);
    }

}