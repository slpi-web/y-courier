<?php

class IndexController extends BaseAdminController
{

    public $defaultAction = 'index';

    public function actionIndex()
    {
        $this->render('index');
    }

}