<?php
/**
 * @var $this ReportController
 * @var $reportHelper ReportHelper
 * @var $lastTime int
 */

$this->pageTitle = Yii::t('admin_pagetitle', 'Report Info');

$this->breadcrumbs=array(
    Yii::t('admin_pagetitle', 'Reports'),
);
$this->breadcrumbs=array(
    Yii::t('admin_pagetitle', 'Reports') => array('index'),
    Yii::t('admin_pagetitle', 'Report Info'),
);
?>

<div class="well">
    <p>
        <?php echo Yii::t('view_report', 'Report with this parameters alredy exists.<br /><strong>Ready time</strong>: {lastTime}.<br />What you want to do?', array(
            '{lastTime}' => Yii::app()->dateFormatter->formatDateTime($lastTime, "medium", "medium"),
        )); ?>
    </p>
    <div class="text-center">
        <?php echo CHtml::link(Yii::t('view_report', "Download"), array('download', 'key'=> $reportHelper->getKey()), array('class' => 'btn btn-primary')); ?>
        <?php echo CHtml::link(Yii::t('view_report', "Re-create"), array('create', 'key'=> $reportHelper->getKey()), array('class' => 'btn btn-default')); ?>
    </div>
</div>