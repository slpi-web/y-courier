<?php
/**
 * @var $this AppealController
 * @var $model AppealClient
 */

$this->pageTitle = Yii::t('client_pagetitle', 'Appeal Add');

$this->breadcrumbs=array(
    Yii::t('client_pagetitle', 'Appeals') => array('list'),
    Yii::t('client_pagetitle', 'Appeal Add'),
);
?>

<?php
$this->renderPartial('_form', array(
    'model' => $model,
));