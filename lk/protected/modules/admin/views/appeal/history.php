<?php
/**
 * @var $this AppealController
 * @var $model AppealAdmin
 * @var $iframed boolean
 */

$this->pageTitle = Yii::t('admin_pagetitle', 'Appeal History');

$this->breadcrumbs=array(
    Yii::t('admin_pagetitle', 'Appeals') => array('list'),
    Yii::t('admin_pagetitle', 'Appeal History'),
);

$this->widget('ext.ModelHistory.WModelHistory', array(
    'model' => $model,
    'userAutocompleteAjaxUrl' => array('/admin/ajax/autocompleteUser'),
    'iframed' => $iframed,
));

