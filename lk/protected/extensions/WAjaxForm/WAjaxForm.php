<?php
/**
 * Created by PhpStorm.
 * User: dim
 * Date: 20.06.14
 * Time: 12:33
 */

class WAjaxForm extends CWidget
{
    public $script = '';

    protected $baseUrl = null;

    public function init( )
    {
        parent::init();

        $this->id = $this->getId();

        $dir = dirname(__FILE__) . DIRECTORY_SEPARATOR;
        $this->baseUrl = Yii::app()->getAssetManager()->publish($dir . 'assets', false, -1);
    }

    public function run()
    {
        $clientScript = Yii::app()->getClientScript();


        $clientScript->registerCoreScript('jquery');
        $clientScript->registerScriptFile($this->baseUrl . '/jquery.form.js', CClientScript::POS_HEAD);

        if ($this->script)
            $clientScript->registerScript('wajaxform-'.$this->id, $this->script, CClientScript::POS_END);
    }

} 