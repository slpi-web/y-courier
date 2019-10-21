<?php
/**
 * @var $this CseDeliveryController
 * @var $model CseDeliveryAdmin
 */

$this->pageTitle = Yii::t('admin_pagetitle', 'Cse Delivery History');

$this->breadcrumbs=array(
    Yii::t('admin_pagetitle', 'Cse Delivery') => array('list'),
    Yii::t('admin_pagetitle', 'Cse Delivery History'),
);

$this->widget('ext.ModelHistory.WModelHistory', array(
    'model' => $model,
    'userAutocompleteAjaxUrl' => array('/admin/ajax/autocompleteUser'),
    'iframed' => $iframed,
));