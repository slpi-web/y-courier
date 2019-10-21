<?php
/**
 * @var $this AppealController
 * @var $model AppealDepartamentAdmin
 */

$this->pageTitle = Yii::t('admin_pagetitle', 'Appeal Departament Edit');

$this->breadcrumbs=array(
    Yii::t('admin_pagetitle', 'Appeals') => array('list'),
    Yii::t('admin_pagetitle', 'Appeal Departaments') => array('departamentList'),
    Yii::t('admin_pagetitle', 'Appeal Departament Edit'),
);
?>

<?php
$this->renderPartial('_departament_form', array(
    'model' => $model,
));