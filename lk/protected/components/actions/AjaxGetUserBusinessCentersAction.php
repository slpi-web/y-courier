<?php

Yii::import('application.components.actions.BaseAjaxAction');

class AjaxGetUserBusinessCentersAction extends BaseAjaxAction
{

    public function run($id)
    {
        $result = array(
            'status' => 0,
        );

        $businessCenters = BusinessCenter::model()->byUserId($id)->findAll(array(
            'select' => array('id', 'caption'),
        ));
        if ($businessCenters) {
            $result['status'] = 1;
            $result['data'] = array();
            foreach ($businessCenters as $businessCenter) {
                $result['data'][$businessCenter->id] = $businessCenter->caption;
            }
        }

        $this->response($result);
    }

}