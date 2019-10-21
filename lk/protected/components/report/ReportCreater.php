<?php

class ReportCreater
{

    protected $key = null;
    protected $params = array();

    public function __construct($key, $params)
    {
        $this->key = $key;
        $this->params = $params;

        $this->init();
    }

    protected function init()
    {

    }

    public function check()
    {
        return false;
    }

    public function getResult()
    {
        throw new CHttpException(404, Yii::t('report_creater', 'Report result file does not found.'));
    }


    public function start()
    {

    }

    public function step(array $data)
    {

    }

    public function end()
    {

    }

}