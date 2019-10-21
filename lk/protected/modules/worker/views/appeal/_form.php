<?php
/**
 * @var $this AppealController
 * @var $model AppealWorker
 */

$form = $this->beginWidget(
    'booster.widgets.TbActiveForm',
    array(
        'id' => 'appeal-form',
        'type' => 'horizontal',
        'htmlOptions' => array('class' => 'well'),
    )
); ?>

<?php
echo $form->textFieldGroup($model, 'id', array(
    'widgetOptions' => array(
        'htmlOptions' => array(
            'disabled' => 'disabled'
        ),
    )
));
echo $form->widgetGroup('ext.OP.OPUserSelector', $model, 'user_id', array(
    'widgetOptions' => array(
        'model' => $model,
        'attribute' => 'user_id',
        'ajaxUrl' => array('/worker/ajax/autocompleteUserClient'),
        'options' => array(
            'width' => '100%',
        )
    )
));
echo $form->dropDownListGroup($model, 'business_center_id', array(
    'widgetOptions' => array(
        'data' => DataReceiver::getBusinessCenterList(array(
            'select' => array('id', 'caption'),
            'scopes' => array('byUserId' => array($model->user_id)),
        )),
        'htmlOptions' => array(),
    )
));
echo $form->select2Group($model, 'appeal_departaments', array(
    'widgetOptions' => array(
        'data' => DataReceiver::getAppealDepartamentsList(),
        'asDropDownList' => true,
        'htmlOptions' => array(
            'multiple' => 'multiple'
        ),
    )
));
echo $form->dropDownListGroup($model, 'status', array(
    'widgetOptions' => array(
        'data' => $model->getAvailableStatusLabels(),
        'htmlOptions' => array(),
    )
));
echo $form->textFieldGroup($model, 'timestamp', array(
    'widgetOptions' => array(
        'htmlOptions' => array(
            'value' => Yii::app()->dateFormatter->formatDateTime($model->timestamp, "short", "short"),
            'disabled' => 'disabled'
        ),
    )
));
echo $form->textFieldGroup($model, 'subject');
echo $form->textAreaGroup($model, 'text');

?>

<div class="text-center">
    <?php $this->widget(
        'ext.WLadda.TbLaddaButton',
        array('buttonType' => 'submit', 'label' => Yii::t('view_worker', 'Save'), 'htmlOptions' => array('class' => 'btn-primary'))
    ); ?>
</div>

<?php $this->endWidget(); ?>

<?php
$cs = Yii::app()->getClientScript();
$cs->registerCoreScript('jquery');
$updateUrl = Yii::app()->createUrl('/worker/ajax/getUserBusinessCenters');
$cs->registerScript('update-business-centers', '
    $("#AppealWorker_user_id").change(function(e){
        select = $("#AppealWorker_business_center_id");
        select.empty();
        $.ajax({
            url: "'.$updateUrl.'",
            data: {
                id: $(this).val()
            },
            success: function(response) {
                if (response.status && response.data) {
                    $.each(response.data, function(value,text) {
                        select.append($("<option></option>").attr("value", value).text(text));
                    });
                }
            }
        });
    });
');
