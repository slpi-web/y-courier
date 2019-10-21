<?php
/**
 * @var $this UserController
 * @var $model UserChangePasswordForm
 * @var $displayVariant string
 */

$this->pageTitle=Yii::app()->name . ' - ' . Yii::t('pagetitle', 'user.changePassword');
$this->breadcrumbs=array(
    Yii::t('pagetitle', 'user.changePassword'),
);
?>

<?php $form = $this->beginWidget(
    'booster.widgets.TbActiveForm',
    array(
        'id' => 'reset-password-form',
        'type' => 'horizontal',
        'htmlOptions' => array('class' => 'well'),
    )
); ?>

    <?php if ($displayVariant != 'user') {
        if ($displayVariant == 'long') {
            echo $form->textFieldGroup($model, 'email', array());
            echo $form->textFieldGroup($model, 'key', array());
        }
        echo $form->passwordFieldGroup($model, 'newPassword', array());
    } else {
        echo $form->passwordFieldGroup($model, 'oldPassword', array());
        echo $form->passwordFieldGroup($model, 'newPassword', array());
    }

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
                'label' => Yii::t('view_user_changePassword', 'button.change'),
            )
        );
        ?>
    </div>

<?php $this->endWidget(); ?>