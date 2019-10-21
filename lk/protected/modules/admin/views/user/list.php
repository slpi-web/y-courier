<?php
/**
 * @var $this UserController
 * @var $model UserAdmin
 */

$this->pageTitle = Yii::t('admin_pagetitle', 'Users');

$this->breadcrumbs=array(
    Yii::t('admin_pagetitle', 'Users'),
);
?>

<?php
$this->renderPartial('_grid', array(
    'model' => $model,
));