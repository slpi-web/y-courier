<?php

class WLadda extends CWidget
{

    protected $baseUrl = '';

    public $theme = false;

    public $jQuery = true;

    public $js = '';

    public $jsPosition = CClientScript::POS_READY;

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

        if ($this->theme)
            $clientScript->registerCssFile($this->baseUrl . '/css/ladda.min.css');
        else
            $clientScript->registerCssFile($this->baseUrl . '/css/ladda-themeless.min.css');

        $scriptFilenamePostfix = '.min';
        if (YII_DEBUG)
            $scriptFilenamePostfix ='';

        $clientScript->registerScriptFile($this->baseUrl . '/js/spin'.$scriptFilenamePostfix.'.js', CClientScript::POS_HEAD);
        $clientScript->registerScriptFile($this->baseUrl . '/js/ladda'.$scriptFilenamePostfix.'.js', CClientScript::POS_HEAD);

        if ($this->jQuery) {
            $clientScript->registerCoreScript('jquery');
            $clientScript->registerScriptFile($this->baseUrl . '/js/ladda.jquery'.$scriptFilenamePostfix.'.js', CClientScript::POS_HEAD);
        }

        if ($this->js) {
            $clientScript->registerScript('Ladda#'.$this->id, $this->js, $this->jsPosition);
        }
    }

}