<?php

class BaseAdminController extends BaseController
{

    public $layout = '/layouts/main';

    public function filters() {
        return array(
            'accessControl',
            array('application.extensions.YiiBooster.filters.BoosterFilter - delete'),
        );
    }

    public function accessRules()
    {
        return array(
            array('allow',
                'users'=>array('@'),
                'expression' => '$user->isAdmin()',
            ),

            array('deny',
                'users'=>array('*'),
            ),
        );
    }

}