<?php

class IndexController extends BaseClientController
{

    public $defaultAction = 'index';

    public function actionIndex()
    {
        $this->render('index');
    }

}