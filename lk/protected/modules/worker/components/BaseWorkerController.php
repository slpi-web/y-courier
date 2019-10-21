<?php

class BaseWorkerController extends BaseController
{

    public $layout = '/layouts/main';

    public $headerButton = '';

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
                'expression' => '$user->isWorker()',
            ),

            array('deny',
                'users'=>array('*'),
            ),
        );
    }

}