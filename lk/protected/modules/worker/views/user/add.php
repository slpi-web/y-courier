<?php
/**
 * @var $this UserController
 * @var $model UserWorker
 */

$this->pageTitle = Yii::t('worker_pagetitle', 'User Add');

$this->breadcrumbs=array(
    Yii::t('worker_pagetitle', 'Users') => array('list'),
    Yii::t('worker_pagetitle', 'User Add'),
);
?>

<?php
$this->renderPartial('_form', array(
    'model' => $model,
));