<?php
/**
 * @var $this PostDeliveryController
 * @var $model PostDeliveryAdmin
 */

$this->pageTitle = Yii::t('admin_pagetitle', 'Post Delivery Add');

$this->breadcrumbs=array(
    Yii::t('admin_pagetitle', 'Post Delivery') => array('list'),
    Yii::t('admin_pagetitle', 'Post Delivery Add'),
);
?>

<?php
$this->renderPartial('_form', array(
    'model' => $model,
));