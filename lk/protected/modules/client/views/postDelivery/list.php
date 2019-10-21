<?php
/**
 * @var $this PostDeliveryController
 * @var $model PostDeliveryClient
 */

$this->pageTitle = Yii::t('client_pagetitle', 'Post Delivery');

$this->breadcrumbs=array(
    Yii::t('client_pagetitle', 'Post Delivery'),
);

$this->headerButton = array(
    'link' => $this->createUrl('add'),
    'title' => Yii::t('view_client', 'Add New Post Delivery'),
);
?>

<?php
$this->renderPartial('_grid', array(
    'model' => $model,
));