<?php
/**
 * @var $this PostDeliveryController
 * @var $model PostDeliveryAdmin
 */

$this->pageTitle = Yii::t('admin_pagetitle', 'Post Delivery History');

$this->breadcrumbs=array(
    Yii::t('admin_pagetitle', 'Post Delivery') => array('list'),
    Yii::t('admin_pagetitle', 'Post Delivery History'),
);

$this->widget('ext.ModelHistory.WModelHistory', array(
    'model' => $model,
    'userAutocompleteAjaxUrl' => array('/admin/ajax/autocompleteUser'),
    'iframed' => $iframed,
));