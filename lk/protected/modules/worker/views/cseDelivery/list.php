<?php
/**
 * @var $this CseDeliveryController
 * @var $model CseDeliveryWorker
 */

$this->pageTitle = Yii::t('worker_pagetitle', 'Cse Delivery');

$this->breadcrumbs=array(
    Yii::t('worker_pagetitle', 'Cse Delivery'),
);
?>

<?php
$this->renderPartial('_grid', array(
    'model' => $model,
));