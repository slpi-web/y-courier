<?php
/**
 * @var $this UserController
 * @var $model UserWorker
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
            'disabled' => !$model->isNewRecord
        ),
    )
));
echo $form->dropDownListGroup($model, 'status', array(
    'widgetOptions' => array(
        'data' => $model->getStatusLabels(),
        'htmlOptions' => array(
            'disabled' => !$model->isNewRecord
        ),
    )
));
if ($model->getIsNewRecord())
    echo $form->textFieldGroup($model, 'password');
?>
<div id="type-client"<?php if ($model->type != User::TYPE_CLIENT) echo ' style="display:none;"'; ?>>
    <?php
    echo $form->textFieldGroup($model, 'organization', array(
        'widgetOptions' => array(
            'htmlOptions' => array(
                'disabled' => !$model->isNewRecord
            ),
        )
    ));
    echo $form->textFieldGroup($model, 'phone', array(
        'widgetOptions' => array(
            'htmlOptions' => array(
                'disabled' => !$model->isNewRecord
            ),
        )
    ));
    echo $form->textFieldGroup($model, 'inn', array(
        'widgetOptions' => array(
            'htmlOptions' => array(
                'disabled' => !$model->isNewRecord
            ),
        )
    ));
    echo $form->textFieldGroup($model, 'additional_email');
    echo $form->widgetGroup('ext.OP.OPBusinessCenterSelector', $model, 'business_centers', array(
        'widgetOptions' => array(
            'model' => $model,
            'attribute' => 'business_centers',
            'ajaxUrl' => array('/worker/ajax/autocompleteBusinessCenter'),
            'options' => array(
                'width' => '100%',
                'multiple' => true,
            ),
            'htmlOptions' => array(
                'multiple' => 'multiple'
            ),
        )
    ));
    echo $form->textFieldGroup($model, 'debt', array(
    ));
    echo $form->textFieldGroup($model, 'debt_limit', array(
        'widgetOptions' => array(
            'htmlOptions' => array(
                'disabled' => !$model->isNewRecord
            ),
        )
    ));
    ?>
</div>

<?php
echo $form->textAreaGroup($model, 'comment');
?>

<div class="text-center">
    <?php $this->widget(
        'ext.WLadda.TbLaddaButton',
        array('buttonType' => 'submit', 'label' => Yii::t('view_worker', 'Save'), 'htmlOptions' => array('class' => 'btn-primary'))
    ); ?>
</div>

<?php $this->endWidget(); ?>
