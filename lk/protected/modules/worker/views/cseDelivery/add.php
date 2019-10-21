<?php
/**
 * @var $this CseDeliveryController
 * @var $model CseDeliveryWorker
 */

$this->pageTitle = Yii::t('worker_pagetitle', 'Cse Delivery Add');

$this->breadcrumbs=array(
    Yii::t('worker_pagetitle', 'Cse Delivery') => array('list'),
    Yii::t('worker_pagetitle', 'Cse Delivery Add'),
);
?>

<?php
$this->renderPartial('_form', array(
    'model' => $model,
));