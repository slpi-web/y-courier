<?php
/**
 * @var $this AuthController
 * @var $model UserLoginForm
 * @var $form CActiveForm
 */

$this->pageTitle = Yii::t('admin_pagetitle', 'Login');

$this->breadcrumbs=array(
);
?>


<?php $form = $this->beginWidget(
    'booster.widgets.TbActiveForm',
    array(
        'id' => 'login-form',
        'type' => 'vertical',
        'htmlOptions' => array('class' => 'well narrow'),
    )
); ?>


<?php
echo $form->textFieldGroup($model, 'email', array(
    'labelOptions' => array(
        'class' => 'sr-only',
    ),
));

echo $form->passwordFieldGroup($model, 'password', array(
    'labelOptions' => array(
        'class' => 'sr-only',
    ),
));

if (CaptchaFormModel::isCaptchaEnabled())
    echo $form->captchaGroup($model, 'verifyCode', array());
?>

    <div class="text-center">
        <?php echo $form->checkboxGroup($model, 'rememberMe'); ?>
        <?php if (isset(Yii::app()->params['allowResetPassword']) && Yii::app()->params['allowResetPassword']) { ?>
        <p>
            <?php echo CHtml::link(Yii::t('view_user_login', 'forgot password?'), array('/user/resetPassword')); ?>
        </p>
        <?php } ?>
    </div>

    <div class="text-center">
        <?php
        $this->widget(
            'ext.WLadda.TbLaddaButton',
            array(
                'buttonType' => 'submit',
                'context' => 'primary',
                'label' => Yii::t('view_user_login', 'Login button'),
            )
        );
        ?>
    </div>

<?php $this->endWidget(); ?>