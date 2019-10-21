<?php
/**
 * @var $this AppealController
 * @var $model AppealWorker
 * @var $messagesDataProvider CActiveDataProvider
 * @var $messageModel AppealMessage
 */

$this->pageTitle = Yii::t('worker_pagetitle', 'Appeal View');

$this->breadcrumbs=array(
    Yii::t('worker_pagetitle', 'Appeals') => array('list'),
    Yii::t('worker_pagetitle', 'Appeal View'),
);

if ($model->status != Appeal::STATUS_CLOSED) {
    $this->beginClip('submit');
    $this->widget(
        'ext.WLadda.TbLaddaButton',
        array('buttonType' => 'submit', 'label' => Yii::t('view_worker', 'Save'), 'htmlOptions' => array('class' => 'btn-primary'))
    );
    $this->endClip();

    $this->beginClip('button');

    $form = $this->beginWidget(
        'booster.widgets.TbActiveForm',
        array(
            'id' => 'appeal-form',
            'type' => 'inline',
            'htmlOptions' => array('class' => 'pull-right'),
        )
    );
    echo $form->dropDownListGroup($model, 'status', array(
        'widgetOptions' => array(
            'data' => $model->getAvailableStatusLabels(),
            'htmlOptions' => array(),
        ),
        'append' => '<span class="input-group-btn">' . $this->clips['submit'] . '</span>',
        'appendOptions' => array(
            'isRaw' => true
        ),
    ));
    $this->endWidget();

    $this->endClip();

    $this->headerButton = $this->clips['button'];
}
?>

<?php
$this->widget(
    'booster.widgets.TbDetailView',
    array(
        'data' => $model,
        'attributes' => array(
            array('name' => 'id'),
            array('name' => 'user_id', 'value' => $model->user ? $model->user->display : ''),
            array('name' => 'phone', 'value' => $model->user ? $model->user->phone : '', 'label' => $model->user ? $model->user->getAttributeLabel('phone') : '', 'visible' => $model->user),
            array('name' => 'email', 'value' => $model->user ? $model->user->email : '', 'label' => $model->user ? $model->user->getAttributeLabel('email') : '', 'visible' => $model->user),
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