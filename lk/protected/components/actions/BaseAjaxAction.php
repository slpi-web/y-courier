<?php

class BaseAjaxAction extends CAction {

    public $cacheDays = 5;

    protected function response($data)
    {
        header( 'Content-type: application/json' );
        echo function_exists('json_encode') ? json_encode($data) : CJSON::encode($data);

    }


}