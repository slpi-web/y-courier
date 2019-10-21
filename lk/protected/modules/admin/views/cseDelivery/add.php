<?php
/**
 * @var $this CseDeliveryController
 * @var $model CseDeliveryAdmin
 */

$this->pageTitle = Yii::t('admin_pagetitle', 'Cse Delivery Add');

$this->breadcrumbs=array(
    Yii::t('admin_pagetitle', 'Cse Delivery') => array('list'),
    Yii::t('admin_pagetitle', 'Cse Delivery Add'),
);
?>

<?php
$this->renderPartial('_form', array(
    'model' => $model,
));