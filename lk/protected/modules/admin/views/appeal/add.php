<?php
/**
 * @var $this AppealController
 * @var $model AppealAdmin
 */

$this->pageTitle = Yii::t('admin_pagetitle', 'Appeal Add');

$this->breadcrumbs=array(
    Yii::t('admin_pagetitle', 'Appeals') => array('list'),
    Yii::t('admin_pagetitle', 'Appeal Add'),
);
?>

<?php
$this->renderPartial('_form', array(
    'model' => $model,
));