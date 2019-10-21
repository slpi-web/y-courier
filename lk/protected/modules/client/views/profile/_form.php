<?php
/**
 * @var $this UserController
 * @var $model UserAdmin
 */

$form = $this->beginWidget(
    'booster.widgets.TbActiveForm',
    array(
        'id' => 'user-form',
        'type' => 'horizontal',
        'htmlOptions' => array('class' => 'well'),
    )
); ?>

<?php
echo $form->textFieldGroup($model, 'email', array(
    'widgetOptions' => array(
        'htmlOptions' => array(
            'disabled' => true
        ),
    )
));

echo $form->textFieldGroup($model, 'organization', array(
    'widgetOptions' => array(
        'htmlOptions' => array(
            'disabled' => true
        ),
    )
));
echo $form->textFieldGroup($model, 'phone', array(
    'widgetOptions' => array(
        'htmlOptions' => array(
            'disabled' => true
        ),
    )
));
echo $form->textFieldGroup($model, 'inn', array(
    'widgetOptions' => array(
        'htmlOptions' => array(
            'disabled' => true
        ),
    )
));
echo $form->textFieldGroup($model, 'additional_email');

echo $form->select2Group($model, 'business_centers', array(
    'widgetOptions' => array(
        'data' => DataReceiver::getBusinessCenterList(array(
            'select' => array('id', 'caption'),
            'scopes' => array('byUserId' => array($model->id)),
        )),
        'asDropDownList' => true,
        'htmlOptions' => array(
            'multiple' => 'multiple',
            'disabled' => true,
        ),
    )
));
echo $form->textFieldGroup($model, 'debt', array(
    'widgetOptions' => array(
        'htmlOptions' => array(
            'disabled' => true
        ),
    )
));
echo $form->textFieldGroup($model, 'debt_limit', array(
    'widgetOptions' => array(
        'htmlOptions' => array(
            'disabled' => true
        ),
    )
));
?>


<div class="text-center">
    <?php $this->widget(
        'ext.WLadda.TbLaddaButton',
        array('buttonType' => 'submit', 'label' => Yii::t('view_client', 'Save'), 'htmlOptions' => array('class' => 'btn-primary'))
    ); ?>
</div>

<?php $this->endWidget(); ?>
