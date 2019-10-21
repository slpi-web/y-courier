<?php
/**
 * @var $this PostDeliveryController
 * @var $model PostDeliveryWorker
 */

$this->pageTitle = Yii::t('worker_pagetitle', 'Post Delivery');

$this->breadcrumbs=array(
    Yii::t('worker_pagetitle', 'Post Delivery'),
);
?>

<?php
$this->renderPartial('_grid', array(
    'model' => $model,
));