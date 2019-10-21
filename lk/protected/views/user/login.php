<?php
/* @var $this UserController */
/* @var $model UserLoginForm */

$this->pageTitle=Yii::app()->name . ' - ' . Yii::t('pagetitle', 'user.login');
$this->breadcrumbs=array(
    Yii::t('pagetitle', 'user.login'),
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
                'label' => Yii::t('view_user_login', 'button.login'),
            )
        );
        ?>
    </div>

<?php $this->endWidget(); ?>