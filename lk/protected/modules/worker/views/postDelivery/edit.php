<?php
/**
 * @var $this PostDeliveryController
 * @var $model PostDeliveryWorker
 */

$this->pageTitle = Yii::t('worker_pagetitle', 'Post Delivery Edit');

$this->breadcrumbs=array(
    Yii::t('worker_pagetitle', 'Post Delivery') => array('list'),
    Yii::t('worker_pagetitle', 'Post Delivery Edit'),
);
?>

<?php
$this->renderPartial('_form', array(
    'model' => $model,
));