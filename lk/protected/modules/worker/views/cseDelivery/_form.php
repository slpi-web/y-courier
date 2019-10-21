<?php
/**
 * @var $this CseDeliveryController
 * @var $model CseDeliveryWorker
 */

$modelName = 'CseDeliveryWorker';

$form = $this->beginWidget(
    'booster.widgets.TbActiveForm',
    array(
        'id' => 'cse-delivery-form',
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
echo $form->textFieldGroup($model, 'timestamp', array(
    'widgetOptions' => array(
        'htmlOptions' => array(
            'value' => Yii::app()->dateFormatter->formatDateTime($model->timestamp, "short", "short"),
            'disabled' => 'disabled'
        ),
    )
));
?>

<div class="row">
    <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">
                    <?php echo Yii::t('view_cse_form', 'Sender'); ?>
                    <div class="pull-right negative-mt-6">
                        <div class="btn-group">
                            <a class="btn btn-default cse-addressbook-save" data-type="sender" title="<?php echo Yii::t('view_addressbook', 'Save to addressbook'); ?>" data-toggle="tooltip"><i class="glyphicon glyphicon-floppy-disk"></i></a>
                            <a class="btn btn-default cse-addressbook-open" data-type="sender" title="<?php echo Yii::t('view_addressbook', 'Select from addressbook'); ?>" data-toggle="tooltip"><i class="glyphicon glyphicon-book"></i></a>
                        </div>
                    </div>
                </h3>
            </div>
            <div class="panel-body">
                <?php
                echo $form->textFieldGroup($model, 'sender');
                echo $form->textFieldGroup($model, 'sender_contact');
                $this->widget('ext.Cse.CseCitySelectorWidget', array(
                    'id' => 'sender',
                    'form' => $form,
                    'model' => $model,
                    'type' => 'sender',
                    'ajaxCountryInfoUrl' => array('/worker/ajax/getCseCountyInfo'),
                    'ajaxRegionAutocompleteUrl' => array('/worker/ajax/autocompleteCseRegion'),
                    'ajaxAreaAutocompleteUrl' => array('/worker/ajax/autocompleteCseArea'),
                    'ajaxCityAutocompleteUrl' => array('/worker/ajax/autocompleteCseCity'),
                ));
                echo $form->textFieldGroup($model, 'sender_address');
                echo $form->textFieldGroup($model, 'sender_post_index');
                echo $form->textFieldGroup($model, 'sender_phone');
                echo $form->textFieldGroup($model, 'sender_email');
                echo $form->textAreaGroup($model, 'sender_info', array(
                    'widgetOptions' => array(
                        'htmlOptions' => array(
                            'class' => 'vresize',
                        ),
                    ),
                ));
                ?>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">
                    <?php echo Yii::t('view_cse_form', 'Recipient'); ?>
                    <div class="pull-right negative-mt-6">
                        <div class="btn-group">
                            <a class="btn btn-default cse-addressbook-save" data-type="recipient" title="<?php echo Yii::t('view_addressbook', 'Save to addressbook'); ?>" data-toggle="tooltip"><i class="glyphicon glyphicon-floppy-disk"></i></a>
                            <a class="btn btn-default cse-addressbook-open" data-type="recipient" title="<?php echo Yii::t('view_addressbook', 'Select from addressbook'); ?>" data-toggle="tooltip"><i class="glyphicon glyphicon-book"></i></a>
                        </div>
                    </div>
                </h3>
            </div>
            <div class="panel-body">
                <?php
                echo $form->textFieldGroup($model, 'recipient');
                echo $form->textFieldGroup($model, 'recipient_contact');
                $this->widget('ext.Cse.CseCitySelectorWidget', array(
                    'id' => 'recipient',
                    'form' => $form,
                    'model' => $model,
                    'type' => 'recipient',
                    'ajaxCountryInfoUrl' => array('/worker/ajax/getCseCountyInfo'),
                    'ajaxRegionAutocompleteUrl' => array('/worker/ajax/autocompleteCseRegion'),
                    'ajaxAreaAutocompleteUrl' => array('/worker/ajax/autocompleteCseArea'),
                    'ajaxCityAutocompleteUrl' => array('/worker/ajax/autocompleteCseCity'),
                ));
                echo $form->textFieldGroup($model, 'recipient_address');
                echo $form->textFieldGroup($model, 'recipient_post_index');
                echo $form->textFieldGroup($model, 'recipient_phone');
                echo $form->textFieldGroup($model, 'recipient_email');
                echo $form->textAreaGroup($model, 'recipient_info', array(
                    'widgetOptions' => array(
                        'htmlOptions' => array(
                            'class' => 'vresize',
                        ),
                    ),
                ));
                ?>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><?php echo Yii::t('view_cse_form', 'Cargo'); ?></h3>
            </div>
            <div class="panel-body">
                <?php
                $disabled = true;
                if ($model->sender_city && $model->recipient_city) {
                    $senderCity = CseCity::model()->active()->findByPk($model->sender_city);
                    $recipientCity = CseCity::model()->active()->findByPk($model->recipient_city);
                    if ($senderCity && $recipientCity) {
                        if ($senderCity->country_id != $recipientCity->country_id) {
                            $disabled = false;
                        }
                    }
                }
                ?>
                <div class="form-group">
                    <?php echo $form->labelEx($model, 'customs_value', array('class' => 'col-sm-3 control-label')); ?>
                    <div class="col-sm-9">
                        <div class="row">
                            <div class="col-xs-6">
                                <?php echo $form->textField($model, 'customs_value', array('class' => 'form-control', 'disabled' =>$disabled)); ?>
                            </div>
                            <div class="col-xs-6">
                                <?php echo $form->dropDownList($model, 'customs_currency', $model->getCustomsCurrencyLabels(), array('class' => 'form-control', 'disabled' => $disabled)); ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
                echo $form->dropDownListGroup($model, 'cargo_type', array(
                    'widgetOptions' => array(
                        'data' => $model->getCargoTypeLabels(),
                        'htmlOptions' => array(
                        ),
                    )
                ));
                echo $form->textFieldGroup($model, 'cargo_seats_number', array(
                    'widgetOptions' => array(
                        'htmlOptions' => array(
                            'class' => 'narrow',
                        ),
                    ),
                ));
                echo $form->textFieldGroup($model, 'cargo_weight', array(
                    'append' => Yii::t('view_cse_form', 'kg.'),
                ));
                ?>
                <div class="form-group">
                    <label class="col-sm-3 control-label">
                        <?php echo Yii::t('view_cse_form', 'Dimensions'); ?>
                    </label>
                    <div class="col-sm-9">
                        <div class="form-inline">
                            <div class="form-group">
                                <?php echo $form->textField($model, 'cargo_width', array('class' => 'form-control', 'style' => 'width: 70px;')); ?>
                            </div>
                            <span class="form-text">*</span>
                            <div class="form-group">
                                <?php echo $form->textField($model, 'cargo_length', array('class' => 'form-control', 'style' => 'width: 70px;')); ?>
                            </div>
                            <span class="form-text">*</span>
                            <div class="form-group">
                                <?php echo $form->textField($model, 'cargo_height', array('class' => 'form-control', 'style' => 'width: 70px;')); ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
                echo $form->textAreaGroup($model, 'cargo_description', array(
                    'widgetOptions' => array(
                        'htmlOptions' => array(
                            'class' => 'vresize',
                        ),
                    ),
                ));
                ?>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><?php echo Yii::t('view_cse_form', 'Delivery terms'); ?></h3>
            </div>
            <div class="panel-body">
                <?php
                echo $form->dropDownListGroup($model, 'delivery_method', array(
                    'widgetOptions' => array(
                        'data' => $model->getAvailableDeliveryMethodLabels(),
                        'htmlOptions' => array(
                        ),
                    )
                ));
                echo $form->dropDownListGroup($model, 'urgency_id', array(
                    'widgetOptions' => array(
                        'data' => $model->getAvailableUrgencyLabels(),
                        'htmlOptions' => array(
                        ),
                    )
                ));
                echo $form->radioButtonListGroup($model, 'payer', array(
                    'widgetOptions' => array(
                        'data' => $model->getPayerLabels(),
                        'htmlOptions' => array(
                        ),
                    )
                ));
                echo $form->dropDownListGroup($model, 'payment_method', array(
                    'widgetOptions' => array(
                        'data' => $model->getPaymentMethodLabels(),
                        'htmlOptions' => array(
                            'options' => $model->getAvailablePaymentMethodOptions()
                        ),
                    )
                ));
                echo $form->datePickerGroup($model, 'take_date',
                    array(
                        'widgetOptions' => array(
                            'options' => array(
                            ),
                        ),
                        'prepend' => '<i class="glyphicon glyphicon-calendar"></i>'
                    )
                );
                ?>
                <div class="form-group">
                    <?php echo CHtml::label(Yii::t('view_cse_form', 'Take time'), $modelName.'_take_time', array('class' => 'col-sm-3 control-label')); ?>
                    <div class="col-sm-9">
                        <div class="form-inline">
                            <div class="form-group">
                                <?php echo $form->dropDownList($model, 'take_time_from', $model->getTakeTimeLabels(), array('class' => 'form-control')); ?>
                            </div>
                            <span class="form-text">-</span>
                            <div class="form-group">
                                <?php echo $form->dropDownList($model, 'take_time_to', $model->getTakeTimeLabels(), array('class' => 'form-control')); ?>
                            </div>
                            <span class="form-text">
                                <?php echo Yii::t('view_cse_form', 'time not less 3 hours'); ?>
                            </span>
                        </div>
                    </div>
                </div>
                <?php
                echo $form->textAreaGroup($model, 'comment', array(
                    'widgetOptions' => array(
                        'htmlOptions' => array(
                            'class' => 'vresize',
                        ),
                    ),
                ));
                ?>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6 col-md-offset-3">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><?php echo Yii::t('view_cse_form', 'Additional services'); ?></h3>
            </div>
            <div class="panel-body">
                <div class="form-group">
                    <?php echo CHtml::label($model->getAttributeLabel('insurance_rate'), $modelName.'_insurance_rate', array('class' => 'col-sm-3 control-label')); ?>
                    <div class="col-sm-9">
                        <div class="col-xs-2">
                            <?php echo CHtml::checkBox('insurance_rate_status', ($model->insurance_rate === null) ? false : true, array('class' => 'form-control')); ?>
                        </div>
                        <div class="col-xs-7">
                            <div class="input-group">
                                <?php echo $form->textField($model, 'insurance_rate', array(
                                    'class' => 'form-control',
                                    'disabled' => ($model->insurance_rate === null) ? true : false,
                                )); ?>
                                <span class="input-group-addon"><?php echo Yii::t('view_cse_form', 'rub.'); ?></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <?php echo CHtml::label($model->getAttributeLabel('declared_value_rate'), $modelName.'_declared_value_rate', array('class' => 'col-sm-3 control-label')); ?>
                    <div class="col-sm-9">
                        <div class="col-xs-2">
                            <?php echo CHtml::checkBox('declared_value_rate_status', ($model->insurance_rate === null) ? false : true, array('class' => 'form-control')); ?>
                        </div>
                        <div class="col-xs-7">
                            <div class="input-group">
                                <?php echo $form->textField($model, 'declared_value_rate', array(
                                    'class' => 'form-control',
                                    'disabled' => ($model->insurance_rate === null) ? true : false,
                                )); ?>
                                <span class="input-group-addon"><?php echo Yii::t('view_cse_form', 'rub.'); ?></span>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
				/*
                echo $form->maskedTextFieldGroup($model, 'notify_phone', array(
                    'widgetOptions' => array(
                        'mask' => '+7 (999) 999-99-99',
                    ),
                ));
                echo $form->textFieldGroup($model, 'notify_email');
				*/
                ?>
            </div>
        </div>
    </div>
</div>



<div class="text-center">
    <?php $this->widget(
        'ext.WLadda.TbLaddaButton',
        array('buttonType' => 'submit', 'label' => Yii::t('view_worker', 'Save'), 'htmlOptions' => array('class' => 'btn-primary'))
    ); ?>
    <?php $this->widget(
        'ext.WLadda.TbLaddaButton',
        array('buttonType' => 'submit', 'label' => Yii::t('view_worker', 'Save and Sync'), 'htmlOptions' => array('class' => 'btn-warning', 'name' => 'sync'))
    ); ?>
</div>

<?php $this->endWidget(); ?>

<?php
$this->widget('ext.Cse.CseAddressBookWidget', array(
    'saveUrl' => array('/worker/cseDelivery/addressbook', 'action' => 'save'),
    'openUrl' => array('/worker/cseDelivery/addressbook', 'action' => 'open'),
    'userSelect' => true,
    'modelName' => $modelName,
));

$cs = Yii::app()->getClientScript();
$cs->registerCoreScript('jquery');
$userInfoUrl = Yii::app()->createUrl('/worker/ajax/getUserData');
$defaultLabel = $model->getPayerLabels(false);
$defaultLabel = $defaultLabel[CseDelivery::PAYER_CUSTOMER];
$cs->registerScript('update-organization', '
    $("#'.$modelName.'_user_id").change(function(e){
        $.ajax({
            url: "'.$userInfoUrl.'",
            data: {
                id: $(this).val()
            },
            success: function(response) {
                var text = "'.$defaultLabel.'";
                if (response.status && response.data) {
                    if (response.data.organization) {
                        text = response.data.organization;
                    }
                }
                $("#'.$modelName.'_payer_0").parent().contents().filter(function(){ return this.nodeType == 3; }).replaceWith(text);
            }
        });
    });
');
$cs->registerScript('additional-switches', '
    $("#insurance_rate_status").change(function(e){
        if ($(this).is(":checked")) {
            $("#'.$modelName.'_insurance_rate").removeAttr("disabled");
        } else {
            $("#'.$modelName.'_insurance_rate").attr("disabled", "disabled").val("");
        }
    });
    $("#declared_value_rate_status").change(function(e){
        if ($(this).is(":checked")) {
            $("#'.$modelName.'_declared_value_rate").removeAttr("disabled");
        } else {
            $("#'.$modelName.'_declared_value_rate").attr("disabled", "disabled").val("");
        }
    });
');

$payerIds = array_keys($model->getPayerLabels());
foreach ($payerIds as &$payerId) {
    $payerId = '#'.$modelName.'_payer_'.$payerId;
}
$payerIds = implode(',', $payerIds);
$cs->registerScript('payment-switches', '
    $("'.$payerIds.'").change(function(e){
        if ($(this).is(":checked")) {
            $("#'.$modelName.'_payment_method").children().attr("disabled", "disabled");
            if ($(this).attr("value") == '.CseDelivery::PAYER_CUSTOMER.') {
                $("#'.$modelName.'_payment_method").val('.CseDelivery::PAYMENT_METHOD_CASHLESS.').children(\'[value="'.CseDelivery::PAYMENT_METHOD_CASHLESS.'"]\').removeAttr("disabled");
            } else {
                $("#'.$modelName.'_payment_method").val('.CseDelivery::PAYMENT_METHOD_CASH.').children(\'[value="'.CseDelivery::PAYMENT_METHOD_CASH.'"]\').removeAttr("disabled");
            }
        }
    });
');

$cs->registerScript('counry-switches', '
    $("#sender-country_id, #recipient-country_id").select2().on("change", function (e) {
        if ($("#sender-country_id").select2("val") != $("#recipient-country_id").select2("val")) {
            $("#'.$modelName.'_customs_value, #'.$modelName.'_customs_currency").removeAttr("disabled");
        } else {
            $("#'.$modelName.'_customs_value, #'.$modelName.'_customs_currency").attr("disabled", "disabled");
            $("#'.$modelName.'_customs_value").val("");
        }
    });
');
