<?php
/**
 * @var $this BusinessCenterController
 * @var $model BusinessCenterAdmin
 */

$this->pageTitle = Yii::t('admin_pagetitle', 'Business Centers');

$this->breadcrumbs=array(
    Yii::t('admin_pagetitle', 'Business Centers'),
);
?>

<?php
$this->renderPartial('_grid', array(
    'model' => $model,
));