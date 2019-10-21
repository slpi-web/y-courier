<?php
/**
 * @var $this BusinessCenterController
 * @var $model BusinessCenterAdmin
 */

$this->pageTitle = Yii::t('admin_pagetitle', 'Business Center Edit');

$this->breadcrumbs=array(
    Yii::t('admin_pagetitle', 'Business Centers') => array('list'),
    Yii::t('admin_pagetitle', 'Business Center Edit'),
);
?>

<?php
$this->renderPartial('_form', array(
    'model' => $model,
));