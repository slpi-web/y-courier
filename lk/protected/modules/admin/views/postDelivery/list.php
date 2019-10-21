<?php
/**
 * @var $this PostDeliveryController
 * @var $model PostDeliveryAdmin
 */

$this->pageTitle = Yii::t('admin_pagetitle', 'Post Delivery');

$this->breadcrumbs=array(
    Yii::t('admin_pagetitle', 'Post Delivery'),
);
?>

<?php
$this->renderPartial('_grid', array(
    'model' => $model,
));