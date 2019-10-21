<?php
/**
 * @var $this ReportController
 * @var $reportHelper ReportHelper
 * @var $lastTime int
 */

$this->pageTitle = Yii::t('worker_pagetitle', 'Report Result');

$this->breadcrumbs=array(
    Yii::t('worker_pagetitle', 'Reports') => array('index'),
    Yii::t('worker_pagetitle', 'Report Result'),
);
?>

<div class="well">
    <p>
        <?php echo Yii::t('view_report', 'Your report has ready.'); ?>
    </p>
    <div class="text-center">
        <?php echo CHtml::link(Yii::t('view_report', "Download"), array('download', 'key'=> $reportHelper->getKey()), array('class' => 'btn btn-primary')); ?>
    </div>
</div>