<?php
/**
 * @var $this AppealController
 * @var $model AppealAdmin
 */

$this->pageTitle = Yii::t('admin_pagetitle', 'Appeals');

$this->breadcrumbs=array(
    Yii::t('admin_pagetitle', 'Appeals'),
);
?>

<?php

$this->renderPartial('_grid', array(
    'model' => $model,
));