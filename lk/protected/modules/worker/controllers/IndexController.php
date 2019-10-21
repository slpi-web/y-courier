<?php

class IndexController extends BaseWorkerController
{

    public $defaultAction = 'index';

    public function actionIndex()
    {
        $this->render('index');
    }

}