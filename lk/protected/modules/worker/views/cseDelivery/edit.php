<?php
/**
 * @var $this CseDeliveryController
 * @var $model CseDeliveryWorker
 */

$this->pageTitle = Yii::t('worker_pagetitle', 'Cse Delivery Edit');

$this->breadcrumbs=array(
    Yii::t('worker_pagetitle', 'Cse Delivery') => array('list'),
    Yii::t('worker_pagetitle', 'Cse Delivery Edit'),
);
?>

<?php
$this->renderPartial('_form', array(
    'model' => $model,
));