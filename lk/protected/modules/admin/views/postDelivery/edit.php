<?php
/**
 * @var $this PostDeliveryController
 * @var $model PostDeliveryAdmin
 */

$this->pageTitle = Yii::t('admin_pagetitle', 'Post Delivery Edit');

$this->breadcrumbs=array(
    Yii::t('admin_pagetitle', 'Post Delivery') => array('list'),
    Yii::t('admin_pagetitle', 'Post Delivery Edit'),
);
?>

<?php
$this->renderPartial('_form', array(
    'model' => $model,
));

$this->widget('ext.ModelHistory.WHistoryButton', array(
    'url' => array('/admin/postDelivery/history', 'id' => $model->id),
    'ajaxUrl' => array('/admin/postDelivery/history', 'id' => $model->id, 'light' => true),
));