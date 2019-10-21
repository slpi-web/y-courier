<?php
/**
 * @var $this PostDeliveryController
 * @var $model PostDeliveryClient
 */

$form = $this->beginWidget(
    'booster.widgets.TbActiveForm',
    array(
        'id' => 'post-delivery-form',
        'type' => 'horizontal',
        'htmlOptions' => array(
            'class' => 'well',
            'enctype'=>'multipart/form-data',
        ),
    )
); ?>

<?php
echo $form->textFieldGroup($model, 'organization', array(
    'label' => $model->getAttributeLabel('organization'),
    'labelOptions' => array(
        'required' => true,
    ),
    'widgetOptions' => array(
        'htmlOptions' => array(
            'disabled' => true,
        ),
    )
));
echo $form->dropDownListGroup($model, 'business_center_id', array(
    'widgetOptions' => array(
        'data' => DataReceiver::getBusinessCenterList(array(
            'select' => array('id', 'caption'),
            'scopes' => array('byUserId' => array($model->user_id)),
        )),
        'htmlOptions' => array(),
    )
));
echo $form->textFieldGroup($model, 'office');
if ($model->isNewRecord) {
    echo $form->fileFieldGroup($model, 'file');
}
echo $form->maskedTextFieldGroup($model, 'letters_count', array(
    'widgetOptions' => array(
        'mask' => '9?9999',
        'htmlOptions' => array(),
    )
));
echo $form->textAreaGroup($model, 'comment');
?>

<div class="text-center">
    <?php $this->widget(
        'ext.WLadda.TbLaddaButton',
        array('buttonType' => 'submit', 'label' => Yii::t('view_client', 'Save'), 'htmlOptions' => array('class' => 'btn-primary'))
    ); ?>
</div>

<?php $this->endWidget(); ?>

