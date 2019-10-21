<?php
/**
 * @var $this BusinessCenterController
 * @var $model BusinessCenterAdmin
 */

$this->pageTitle = Yii::t('admin_pagetitle', 'Business Center Add');

$this->breadcrumbs=array(
    Yii::t('admin_pagetitle', 'Business Centers') => array('list'),
    Yii::t('admin_pagetitle', 'Business Center Add'),
);
?>

<?php
$this->renderPartial('_form', array(
    'model' => $model,
));