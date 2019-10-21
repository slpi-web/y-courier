<?php
/**
 * @var $this CleanController
 * @var $status boolean
 * @var $log string
 */

$this->pageTitle = Yii::t('admin_pagetitle', 'Clean System');

$this->breadcrumbs=array(
    Yii::t('admin_pagetitle', 'Clean System'),
);
?>

<div class="well">
    <?php if (!$status) { ?>
        <div class="btn-group">
            <a class="btn btn-primary" href="<?php echo $this->createUrl('index', array('cache' => true)) ?>"><?php echo Yii::t('view_admin', 'Clear Cache'); ?></a>
            <a class="btn btn-primary" href="<?php echo $this->createUrl('index', array('assets' => true)) ?>"><?php echo Yii::t('view_admin', 'Clear Assets'); ?></a>
            <a class="btn btn-primary" href="<?php echo $this->createUrl('index', array('export' => true)) ?>"><?php echo Yii::t('view_admin', 'Clear Reports'); ?></a>
        </div>
    <?php } else { ?>
        <h2><?php echo Yii::t('view_admin', 'Clear Done'); ?></h2>
        <p>
            <?php
            if (is_array($log))
                echo implode('<br />', $log);
            ?>
        </p>
    <?php } ?>
</div>