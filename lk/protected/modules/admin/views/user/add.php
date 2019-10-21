<?php
/**
 * @var $this UserController
 * @var $model UserAdmin
 */

$this->pageTitle = Yii::t('admin_pagetitle', 'User Add');

$this->breadcrumbs=array(
    Yii::t('admin_pagetitle', 'Users') => array('list'),
    Yii::t('admin_pagetitle', 'User Add'),
);
?>

<?php
$this->renderPartial('_form', array(
    'model' => $model,
));