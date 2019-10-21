<?php
/**
 * @var $this CseDeliveryController
 * @var $model CseDeliveryCalcForm
 */

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

<div class="row">
    <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">
                    <?php echo Yii::t('view_cse_form', 'Sender'); ?>
                </h3>
            </div>
            <div class="panel-body">
                <?php
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
                ?>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">
                    <?php echo Yii::t('view_cse_form', 'Recipient'); ?>
                </h3>
            </div>
            <div class="panel-body">
                <?php
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
                echo $form->dropDownListGroup($model, 'urgency_id', array(
                    'widgetOptions' => array(
                        'data' => $model->getAvailableUrgencyLabels(),
                        'htmlOptions' => array(
                        ),
                    )
                ));
                ?>
            </div>
        </div>
    </div>
</div>

<div class="text-center">
    <?php $this->widget(
        'ext.WLadda.TbLaddaButton',
        array('buttonType' => 'submit', 'label' => Yii::t('view_worker', 'Calculate'), 'htmlOptions' => array('class' => 'btn-primary'))
    ); ?>
</div>
<?php $this->endWidget(); ?>
