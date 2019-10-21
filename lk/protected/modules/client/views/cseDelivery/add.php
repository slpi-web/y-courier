<?php
/**
 * @var $this CseDeliveryController
 * @var $model CseDeliveryClient
 */

$this->pageTitle = Yii::t('client_pagetitle', 'Cse Delivery Add');

$this->breadcrumbs=array(
    Yii::t('client_pagetitle', 'Cse Delivery') => array('list'),
    Yii::t('client_pagetitle', 'Cse Delivery Add'),
);
?>

<?php
$this->renderPartial('_form', array(
    'model' => $model,
));