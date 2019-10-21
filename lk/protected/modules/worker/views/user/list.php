<?php
/**
 * @var $this UserController
 * @var $model UserWorker
 */

$this->pageTitle = Yii::t('worker_pagetitle', 'Users');

$this->breadcrumbs=array(
    Yii::t('worker_pagetitle', 'Users'),
);
?>

<?php
$this->renderPartial('_grid', array(
    'model' => $model,
));