<?php
/**
 * @var $this PostDeliveryController
 * @var $model PostDeliveryWorker
 */

$form = $this->beginWidget(
    'booster.widgets.TbActiveForm',
    array(
        'id' => 'post-delivery-form',
        'type' => 'horizontal',
        'htmlOptions' => array(
            'class' => 'well',
            'enctype'=>'multipart/form-data',
        ),
    )
); ?>

<?php
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
echo $form->dropDownListGroup($model, 'status', array(
    'widgetOptions' => array(
        'data' => $model->getStatusLabels(),
        'htmlOptions' => array(
        ),
    )
));
echo $form->textFieldGroup($model, 'organization', array(
    'label' => $model->getAttributeLabel('organization'),
    'labelOptions' => array(
        'required' => true,
    ),
    'widgetOptions' => array(
        'htmlOptions' => array(
            'disabled' => true,
        ),
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
echo $form->textFieldGroup($model, 'office');
echo $form->textFieldGroup($model, 'timestamp', array(
    'widgetOptions' => array(
        'htmlOptions' => array(
            'value' => Yii::app()->dateFormatter->formatDateTime($model->timestamp, "short", "short"),
            'disabled' => 'disabled'
        ),
    )
));
if ($model->isNewRecord) {
    echo $form->fileFieldGroup($model, 'file');
}
echo $form->maskedTextFieldGroup($model, 'letters_count', array(
    'widgetOptions' => array(
        'mask' => '9?9999',
        'htmlOptions' => array(),
    )
));
echo $form->textAreaGroup($model, 'comment');
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
$updateUrl = Yii::app()->createUrl('/worker/ajax/getUserData');
$cs->registerScript('update-business-centers', '
    $("#PostDeliveryWorker_user_id").change(function(e){
        select = $("#PostDeliveryWorker_business_center_id");
        select.empty();
        $.ajax({
            url: "'.$updateUrl.'",
            data: {
                id: $(this).val()
            },
            success: function(response) {
                if (response.status && response.data) {
                    if (response.data.organization)
                        $("#PostDeliveryWorker_organization").val(response.data.organization).attr("value", response.data.organization);
                    if (response.data.business_centers) {
                        $.each(response.data.business_centers, function(value,text) {
                            select.append($("<option></option>").attr("value", value).text(text));
                        });
                    }
                }
            }
        });
    });
');
