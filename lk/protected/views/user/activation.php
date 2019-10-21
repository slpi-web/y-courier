<?php
/*
* @var $this UserController
* @var $model UserActivationForm
*/

$this->pageTitle=Yii::app()->name . ' - ' . Yii::t('pagetitle', 'user.activation');
$this->breadcrumbs=array(
    Yii::t('pagetitle', 'user.activation'),
);
?>

<?php $form = $this->beginWidget(
    'booster.widgets.TbActiveForm', array(
        'id' => 'activation-form',
        'type' => 'horizontal',
        'enableAjaxValidation' => false,
        'enableClientValidation' => true,
        'htmlOptions' => array(
            'class' => 'well'
        ),
    )
); ?>

    <?php
    echo $form->textFieldGroup($model, 'email');
    echo $form->passwordFieldGroup($model, 'activationToken');
    ?>

    <div class="text-center">
        <?php
        $this->widget(
            'ext.WLadda.TbLaddaButton',
            array(
                'buttonType' => 'submit',
                'context' => 'primary',
                'label' => Yii::t('view_user_activation', 'button.activate'),
            )
        );
        ?>
    </div>

<?php $this->endWidget(); ?>