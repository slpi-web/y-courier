<?php
/**
 * @var $this PostDeliveryController
 * @var $model PostDeliveryClient
 */

$this->pageTitle = Yii::t('client_pagetitle', 'Post Delivery Add');

$this->breadcrumbs=array(
    Yii::t('client_pagetitle', 'Post Delivery') => array('list'),
    Yii::t('client_pagetitle', 'Post Delivery Add'),
);
?>

<?php
$this->renderPartial('_form', array(
    'model' => $model,
));