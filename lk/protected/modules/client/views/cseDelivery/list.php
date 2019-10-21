<?php
/**
 * @var $this CseDeliveryController
 * @var $model CseDeliveryClient
 */

$this->pageTitle = Yii::t('client_pagetitle', 'Cse Delivery');

$this->breadcrumbs=array(
    Yii::t('client_pagetitle', 'Cse Delivery'),
);

$this->headerButton = array(
    'link' => $this->createUrl('add'),
    'title' => Yii::t('view_client', 'Add New Cse Delivery'),
);
?>

<?php
$this->renderPartial('_grid', array(
    'model' => $model,
));