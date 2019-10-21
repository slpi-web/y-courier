<?php

class CseAddressBookWidget extends CWidget
{

    public $view = 'default';
    public $saveUrl = '';
    public $openUrl = '';
    public $modelName = '';
    public $userSelect = false;

    protected $baseUrl;

    public function init()
    {
        $this->id = $this->getId();

        $dir = dirname(__FILE__) . DIRECTORY_SEPARATOR;
        $this->baseUrl = Yii::app()->getAssetManager()->publish($dir . 'assets', false, -1, YII_DEBUG);
    }

    public function run()
    {
        $this->saveUrl = CHtml::normalizeUrl($this->saveUrl);
        $this->openUrl = CHtml::normalizeUrl($this->openUrl);

        $clientScript = Yii::app()->getClientScript();
        $clientScript->registerCoreScript('jquery');
        $clientScript->registerScriptFile($this->baseUrl . '/js/cse-addressbook.jquery.js', CClientScript::POS_HEAD);

        $options = array(
            'saveUrl' => $this->saveUrl,
            'openUrl' => $this->openUrl,
            'modelName' => $this->modelName,
        );

        if ($this->userSelect) {
            $options['userSelect'] = true;
        }

        $clientScript->registerScript('cse_addressbook-'.$this->id, '$.cseAddressBook('.CJavaScript::jsonEncode($options).');', CClientScript::POS_READY);

        echo $this->renderHtml();
    }

    public function renderHtml()
    {
        $this->render('addressBook/'.$this->view, array(
        ));
    }

}