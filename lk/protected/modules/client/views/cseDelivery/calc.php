<?php
/**
 * @var $this CseDeliveryController
 * @var $model CseDeliveryCalcForm
 */

$this->pageTitle = Yii::t('client_pagetitle', 'Cse Delivery Calc');

$this->breadcrumbs=array(
    Yii::t('client_pagetitle', 'Cse Delivery') => array('list'),
    Yii::t('client_pagetitle', 'Cse Delivery Calc'),
);
?>

<?php
$this->renderPartial('_calc_form', array(
    'model' => $model,
));