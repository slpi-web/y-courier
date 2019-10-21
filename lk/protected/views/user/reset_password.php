<?php
/**
 * @var $this UserController
 * @var $model UserResetPasswordForm
 */
?>

<?php $form = $this->beginWidget(
    'booster.widgets.TbActiveForm',
    array(
        'id' => 'reset-password-form',
        'type' => 'horizontal',
        'htmlOptions' => array('class' => 'well'),
    )
); ?>

    <?php
    echo $form->textFieldGroup($model, 'email', array());

    if (CaptchaFormModel::isCaptchaEnabled())
        echo $form->captchaGroup($model, 'verifyCode', array());
    ?>

    <div class="text-center">
        <?php
        $this->widget(
            'ext.WLadda.TbLaddaButton',
            array(
                'buttonType' => 'submit',
                'context' => 'primary',
                'label' => Yii::t('view_user_resetPassword', 'button.reset')
            )
        );
        ?>
    </div>

<?php $this->endWidget(); ?>