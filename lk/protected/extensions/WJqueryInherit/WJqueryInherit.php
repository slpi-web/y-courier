<?php

class WJqueryInherit extends CWidget
{

    protected $baseUrl = '';

    public function init()
    {
        parent::init();

        $this->id = $this->getId();

        $dir = dirname(__FILE__) . DIRECTORY_SEPARATOR;
        $this->baseUrl = Yii::app()->getAssetManager()->publish($dir . 'assets', false, -1, YII_DEBUG);
    }

    public function run()
    {

        $clientScript = Yii::app()->getClientScript();
        $clientScript->registerCoreScript('jquery');

        if (YII_DEBUG)
            $clientScript->registerScriptFile($this->baseUrl . '/js/jquery.inherit.js', CClientScript::POS_HEAD);
        else
            $clientScript->registerScriptFile($this->baseUrl . '/js/jquery.inherit.min.js', CClientScript::POS_HEAD);
    }

}