<?php
/**
 * @var $this AppealController
 * @var $model AppealWorker
 */

$this->pageTitle = Yii::t('worker_pagetitle', 'Appeal Add');

$this->breadcrumbs=array(
    Yii::t('worker_pagetitle', 'Appeals') => array('list'),
    Yii::t('worker_pagetitle', 'Appeal Add'),
);
?>

<?php
$this->renderPartial('_form', array(
    'model' => $model,
));