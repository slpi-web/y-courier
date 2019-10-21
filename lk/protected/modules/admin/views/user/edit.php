<?php
/**
 * @var $this UserController
 * @var $model UserAdmin
 */

$this->pageTitle = Yii::t('admin_pagetitle', 'User Edit');

$this->breadcrumbs=array(
    Yii::t('admin_pagetitle', 'Users') => array('list'),
    Yii::t('admin_pagetitle', 'User Edit'),
);
?>

<?php
$this->renderPartial('_form', array(
    'model' => $model,
));