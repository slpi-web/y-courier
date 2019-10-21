<?php
/**
 * @var $this AppealController
 * @var $model AppealClient
 */

$this->pageTitle = Yii::t('client_pagetitle', 'Appeals');

$this->breadcrumbs=array(
    Yii::t('client_pagetitle', 'Appeals'),
);

$this->headerButton = array(
    'link' => $this->createUrl('add'),
    'title' => Yii::t('view_client', 'Add New Appeal'),
);
?>

<?php
$this->renderPartial('_grid', array(
    'model' => $model,
));