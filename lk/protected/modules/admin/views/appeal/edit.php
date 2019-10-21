<?php
/**
 * @var $this AppealController
 * @var $model AppealAdmin
 * @var $messagesDataProvider CActiveDataProvider
 * @var $messageModel AppealMessage
 */

$this->pageTitle = Yii::t('admin_pagetitle', 'Appeal Edit');

$this->breadcrumbs=array(
    Yii::t('admin_pagetitle', 'Appeals') => array('list'),
    Yii::t('admin_pagetitle', 'Appeal Edit'),
);
?>

<?php
$this->renderPartial('_form', array(
    'model' => $model,
));

$this->renderPartial('_appeal_messages', array(
    'model' => $model,
    'messagesDataProvider' => $messagesDataProvider,
    'messageModel' => $messageModel,
));
?>

<?php
$this->widget('ext.ModelHistory.WHistoryButton', array(
    'url' => array('/admin/appeal/history', 'id' => $model->id),
    'ajaxUrl' => array('/admin/appeal/history', 'id' => $model->id, 'light' => true),
));

