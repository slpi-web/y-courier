<?php
/**
 * @var $this CseDeliveryController
 * @var $model CseDeliveryCalcForm
 */

$this->pageTitle = Yii::t('admin_pagetitle', 'Cse Delivery Calc');

$this->breadcrumbs=array(
    Yii::t('admin_pagetitle', 'Cse Delivery') => array('list'),
    Yii::t('admin_pagetitle', 'Cse Delivery Calc'),
);
?>

<?php
$this->renderPartial('_calc_form', array(
    'model' => $model,
));