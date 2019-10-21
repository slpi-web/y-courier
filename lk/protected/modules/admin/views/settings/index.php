<?php
/**
 * @var $this SettingsController
 * @var $model SettingsForm
 */

$this->pageTitle = Yii::t('admin_pagetitle', 'Settings');

$this->breadcrumbs=array(
    Yii::t('admin_pagetitle', 'Settings'),
);
?>

<?php
$this->renderPartial('_form', array(
    'model' => $model,
));