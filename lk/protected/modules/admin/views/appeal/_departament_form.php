<?php
/**
 * @var $this AppealController
 * @var $model AppealDepartamentAdmin
 */

$form = $this->beginWidget(
    'booster.widgets.TbActiveForm',
    array(
        'id' => 'appeal-departament-form',
        'type' => 'horizontal',
        'htmlOptions' => array('class' => 'well'),
    )
); ?>

<?php
echo $form->textFieldGroup($model, 'caption');
echo $form->dropDownListGroup($model, 'status', array(
    'widgetOptions' => array(
        'data' => $model->getStatusLabels(),
        'htmlOptions' => array(),
    )
));
echo $form->textAreaGroup($model, 'email_list');
?>

<div class="text-center">
    <?php $this->widget(
        'ext.WLadda.TbLaddaButton',
        array('buttonType' => 'submit', 'label' => Yii::t('view_admin', 'Save'), 'htmlOptions' => array('class' => 'btn-primary'))
    ); ?>
</div>

<?php $this->endWidget(); ?>