<?php
/**
 * @var $this AppealController
 * @var $model AppealClient
 * @var $messagesDataProvider CActiveDataProvider
 * @var $messageModel AppealMessage
 */

$this->pageTitle = Yii::t('client_pagetitle', 'Appeal View');

$this->breadcrumbs=array(
    Yii::t('client_pagetitle', 'Appeals') => array('list'),
    Yii::t('client_pagetitle', 'Appeal View'),
);

if ($model->status != Appeal::STATUS_CLOSED) {
    $this->headerButton = array(
        'link' => $this->createUrl('close', array('id' => $model->id)),
        'title' => Yii::t('view_client', 'Close This Appeal'),
    );
}
?>

<?php
$this->widget(
    'booster.widgets.TbDetailView',
    array(
        'data' => $model,
        'attributes' => array(
            array('name' => 'id'),
            array('name' => 'status', 'value' => $model->getStatusLabel()),
            array('name' => 'timestamp', 'value' => Yii::app()->dateFormatter->formatDateTime($model->timestamp, "short", "short")),
            array('name' => 'subject'),
            array('name' => 'business_center_id', 'value' => $model->getDepartaments("<br>"), 'type' => 'raw'),
            array('name' => 'text', 'value' => nl2br($model->text)),
        ),
    )
);

$this->renderPartial('_appeal_messages', array(
    'model' => $model,
    'messagesDataProvider' => $messagesDataProvider,
    'messageModel' => $messageModel,
));