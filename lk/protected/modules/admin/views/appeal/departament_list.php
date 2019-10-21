<?php
/**
 * @var $this AppealController
 * @var $model AppealDepartamentAdmin
 */

$this->pageTitle = Yii::t('admin_pagetitle', 'Appeal Departaments');

$this->breadcrumbs=array(
    Yii::t('admin_pagetitle', 'Appeals') => array('list'),
    Yii::t('admin_pagetitle', 'Appeal Departaments'),
);
?>

<?php
$this->renderPartial('_departament_grid', array(
    'model' => $model,
));