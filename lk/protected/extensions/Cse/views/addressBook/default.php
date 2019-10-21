<?php
/**
 * @var $this CseAddressbookWidget
 */

$this->widget('booster.widgets.TbModalManager', array(
    'htmlOptions' => array(
        'id' => 'addressbook-modal',
    )
));

$this->widget('ext.WAjaxForm.WAjaxForm', array());

$cs = Yii::app()->getClientScript();
$cs->registerCoreScript('jquery');
$cs->registerCoreScript('bbq');
$cs->registerCoreScript('history');
/*$publishUrl = Yii::app()->getAssetManager()->publish(Yii::getPathOfAlias('zii.widgets.assets')).'/gridview';
$cs->registerScriptFile($publishUrl.'/jquery.yiigridview.js',CClientScript::POS_END);*/

$cs->registerPackage('json-grid-view');