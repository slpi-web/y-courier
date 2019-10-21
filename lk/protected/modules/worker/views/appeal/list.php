<?php
/**
 * @var $this AppealController
 * @var $model AppealWorker
 */

$this->pageTitle = Yii::t('worker_pagetitle', 'Appeals');

$this->breadcrumbs=array(
    Yii::t('worker_pagetitle', 'Appeals'),
);
?>

<?php
$this->renderPartial('_grid', array(
    'model' => $model,
));