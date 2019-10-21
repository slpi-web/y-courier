<?php

Yii::import('application.components.actions.BaseAjaxAction');

class AjaxGetUserDataAction extends BaseAjaxAction
{

    public function run($id)
    {
        $result = array(
            'status' => 0,
        );

        $user = User::model()->findByPk($id);
        if ($user) {
            $result['status'] = 1;
            $result['data'] = array(
                'organization' => $user->organization,
            );
            $businessCenters = BusinessCenter::model()->byUserId($id)->findAll(array(
                'select' => array('id', 'caption'),
            ));
            if ($businessCenters) {
                $result['data']['business_centers'] = array();
                foreach ($businessCenters as $businessCenter) {
                    $result['data']['business_centers'][$businessCenter->id] = $businessCenter->caption;
                }
            }
        }

        $this->response($result);
    }

}