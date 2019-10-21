<?php
/**
 * @var $this PostDeliveryController
 * @var $model UserClient
 */

$this->pageTitle = Yii::t('client_pagetitle', 'Profile');

$this->breadcrumbs=array(
    Yii::t('client_pagetitle', 'Profile'),
);
?>

<?php
$this->renderPartial('_form', array(
    'model' => $model,
));