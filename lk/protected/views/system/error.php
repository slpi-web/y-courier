<?php
/**
 * @var $this SystemController
 * @var $error array
 */

$this->pageTitle=Yii::app()->name . ' - ' . Yii::t('pagetitle', 'system.error');
$this->breadcrumbs=array(
    //Yii::t('pagetitle', 'system.error'),
);
?>

<div class="well text-center">
    <h1><div class="ion ion-alert-circled"></div> <?php echo $code; ?></h1>
    <p><?php echo CHtml::encode($message); ?></p>
    <p>
        <?php echo CHtml::link(Yii::t('view_system_error', 'Go to homepage'), array('/index/index'), array('class' => 'btn btn-default btn-lg')); ?>
    </p>
</div>