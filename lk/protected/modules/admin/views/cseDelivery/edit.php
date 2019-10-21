<?php
/**
 * @var $this CseDeliveryController
 * @var $model CseDeliveryAdmin
 */

$this->pageTitle = Yii::t('admin_pagetitle', 'Cse Delivery Edit');

$this->breadcrumbs=array(
    Yii::t('admin_pagetitle', 'Cse Delivery') => array('list'),
    Yii::t('admin_pagetitle', 'Cse Delivery Edit'),
);
?>

<?php
$this->renderPartial('_form', array(
    'model' => $model,
));

$this->widget('ext.ModelHistory.WHistoryButton', array(
    'url' => array('/admin/cseDelivery/history', 'id' => $model->id),
    'ajaxUrl' => array('/admin/cseDelivery/history', 'id' => $model->id, 'light' => true),
));