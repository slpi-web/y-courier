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
echo $form->textFieldGroup($model, 'email');
echo $form->dropDownListGroup($model, 'status', array(
    'widgetOptions' => array(
        'data' => $model->getStatusLabels(),
        'htmlOptions' => array(),
    )
));
echo $form->dropDownListGroup($model, 'type', array(
    'widgetOptions' => array(
        'data' => $model->getTypeLabels(),
        'htmlOptions' => array(),
    )
));
echo $form->textFieldGroup($model, 'password');
?>
<div id="type-client"<?php if ($model->type != User::TYPE_CLIENT) echo ' style="display:none;"'; ?>>
    <?php
    echo $form->textFieldGroup($model, 'organization', array(
        'label' => $model->getAttributeLabel('organization'),
        'labelOptions' => array(
            'required' => true,
        ),
    ));
    echo $form->textFieldGroup($model, 'phone', array(
        'label' => $model->getAttributeLabel('phone'),
        'labelOptions' => array(
            'required' => true,
        ),
    ));
    echo $form->textFieldGroup($model, 'inn', array(
        'label' => $model->getAttributeLabel('inn'),
        'labelOptions' => array(
            'required' => true,
        ),
    ));
    echo $form->textFieldGroup($model, 'additional_email');
    echo $form->widgetGroup('ext.OP.OPBusinessCenterSelector', $model, 'business_centers', array(
        'widgetOptions' => array(
            'model' => $model,
            'attribute' => 'business_centers',
            'ajaxUrl' => array('/admin/ajax/autocompleteBusinessCenter'),
            'options' => array(
                'width' => '100%',
                'multiple' => true,
            ),
            'htmlOptions' => array(
                'multiple' => 'multiple'
            ),
        )
    ));
    /*echo $form->select2Group($model, 'business_centers', array(
        'label' => $model->getAttributeLabel('business_centers'),
        'labelOptions' => array(
            'required' => true,
        ),
        'widgetOptions' => array(
            'data' => DataReceiver::getBusinessCenterList(),
            'asDropDownList' => true,
            'htmlOptions' => array(
                'multiple' => 'multiple'
            ),
        )
    ));*/
    echo $form->textFieldGroup($model, 'debt', array(
        'label' => $model->getAttributeLabel('debt'),
        'labelOptions' => array(
            'required' => true,
        ),
    ));
    echo $form->textFieldGroup($model, 'debt_limit', array(
        'label' => $model->getAttributeLabel('debt_limit'),
        'labelOptions' => array(
            'required' => true,
        ),
    ));
    ?>
</div>
<div id="type-worker"<?php if ($model->type != User::TYPE_WORKER) echo ' style="display:none;"'; ?>>
    <?php
    echo $form->select2Group($model, 'appeal_departaments', array(
        'label' => $model->getAttributeLabel('appeal_departaments'),
        'labelOptions' => array(
            'required' => true,
        ),
        'widgetOptions' => array(
            'data' => DataReceiver::getAppealDepartamentsList(),
            'asDropDownList' => true,
            'htmlOptions' => array(
                'multiple' => 'multiple'
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
        array('buttonType' => 'submit', 'label' => Yii::t('view_admin', 'Save'), 'htmlOptions' => array('class' => 'btn-primary'))
    ); ?>
</div>

<?php $this->endWidget(); ?>

<?php
$cs = Yii::app()->getClientScript();
$cs->registerCoreScript('jquery');
$cs->registerScript('update-user-type', '
    $("#UserAdmin_type").change(function(e){
        switch ($(this).val()) {
            case "'.User::TYPE_CLIENT.'":
                $("#type-client").show();
                $("#type-worker").hide();
                break;
            case "'.User::TYPE_WORKER.'":
                $("#type-worker").show();
                $("#type-client").hide();
                break;
            case "'.User::TYPE_ADMIN.'":
                $("#type-client, #type-worker").hide();
                break;
        }
    });
');
