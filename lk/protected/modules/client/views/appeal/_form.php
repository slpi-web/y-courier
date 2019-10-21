<?php
/**
 * @var $this AppealController
 * @var $model AppealClient
 */

$form = $this->beginWidget(
    'booster.widgets.TbActiveForm',
    array(
        'id' => 'appeal-form',
        'type' => 'horizontal',
        'htmlOptions' => array('class' => 'well'),
    )
); ?>

<?php
echo $form->dropDownListGroup($model, 'business_center_id', array(
    'widgetOptions' => array(
        'data' => DataReceiver::getBusinessCenterList(array(
            'select' => array('id', 'caption'),
            'scopes' => array('byUserId' => array($model->user_id)),
        )),
        'htmlOptions' => array(),
    )
));
echo $form->select2Group($model, 'appeal_departaments', array(
    'widgetOptions' => array(
        'data' => DataReceiver::getAppealDepartamentsList(),
        'asDropDownList' => true,
        'htmlOptions' => array(
        ),
    )
));
echo $form->textFieldGroup($model, 'subject');
echo $form->textAreaGroup($model, 'text');
?>

<div class="text-center">
    <?php $this->widget(
        'ext.WLadda.TbLaddaButton',
        array('buttonType' => 'submit', 'label' => Yii::t('view_client', 'Save'), 'htmlOptions' => array('class' => 'btn-primary'))
    ); ?>
</div>

<?php $this->endWidget(); ?>
