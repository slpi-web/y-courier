<?php
/**
 * @var $this CseDeliveryController
 * @var $model CseDeliveryAdmin
 */

$this->pageTitle = Yii::t('admin_pagetitle', 'Cse Delivery');

$this->breadcrumbs=array(
    Yii::t('admin_pagetitle', 'Cse Delivery'),
);
?>

<?php
$this->renderPartial('_grid', array(
    'model' => $model,
));