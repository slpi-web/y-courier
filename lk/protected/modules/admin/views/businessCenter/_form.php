<?php
/**
 * @var $this BusinessCenterController
 * @var $model BusinessCenter
 */

$form = $this->beginWidget(
    'booster.widgets.TbActiveForm',
    array(
        'id' => 'business-center-form',
        'type' => 'horizontal',
        'htmlOptions' => array('class' => 'well'),
    )
); ?>

<?php
echo $form->textFieldGroup($model, 'caption');
echo $form->textFieldGroup($model, 'address');
echo $form->textFieldGroup($model, 'phone');
?>

<div class="text-center">
    <?php $this->widget(
        'ext.WLadda.TbLaddaButton',
        array('buttonType' => 'submit', 'label' => Yii::t('view_admin', 'Save'), 'htmlOptions' => array('class' => 'btn-primary'))
    ); ?>
</div>

<?php $this->endWidget(); ?>