<?php
/**
 * @var $this CseCitySelectorWidget
 * @var $form TbActiveForm
 * @var $model CseDelivery
 * @var $clasifierModel CseAddressClassifierForm
 * @var $type string
 */

echo CHtml::openTag('div', array('id' => $this->id));

echo $form->select2Group($classifierModel, 'country_id', array(
    'widgetOptions' => array(
        'data' => DataReceiver::getCseCountryList(),
        'asDropDownList' => true,
        'htmlOptions' => array(
            'name' => $type.'AddressClassifier[country_id]',
            'id' => $this->id.'-country_id',
        ),
    )
));

$countryModel = $classifierModel->getCountryModel();
$regionsStatus = false;
$areasStatus = false;
if ($countryModel) {
    $regionsStatus = ($countryModel->getRegionsCount() > 0) ? true : false;
    $areasStatus = ($countryModel->getAreasCount() > 0) ? true : false;
}
?>


<div id="<?php echo $this->id.'-address-classifier-form'?>" class="" style="display:none;">
    <?php
    echo $form->select2Group($classifierModel, 'region_id', array(
        'groupOptions' => array(
            'id' => $this->id.'_classifier-region',
            'style' => (!$regionsStatus) ? 'display: none;' : '',
        ),
        'widgetOptions' => array(
            'data' => array(),
            'asDropDownList' => false,
            'htmlOptions' => array(
                'name' => $type.'AddressClassifier[region_id]',
                'id' => $this->id.'-region_id',
            ),
        )
    ));
    echo $form->select2Group($classifierModel, 'area_id', array(
        'groupOptions' => array(
            'id' => $this->id.'_classifier-area',
            'style' => (!$areasStatus) ? 'display: none;' : '',
        ),
        'widgetOptions' => array(
            'data' => array(),
            'asDropDownList' => false,
            'htmlOptions' => array(
                'name' => $type.'AddressClassifier[area_id]',
                'id' => $this->id.'-area_id',
            ),
        )
    ));
    ?>
</div>

<?php
$initData = '';
if ($model->{$this->getModelCityField()}) {
    $city = CseCity::model()->findByPk($model->{$this->getModelCityField()}, array(
        'with' => array(
            'area' => array(
                'select' => array('id', 'caption'),
            ),
            'region' => array(
                'select' => array('id', 'caption'),
            )
        ),
        'scopes' => array(
            'active'
        ),
    ));
    if ($city) {
        $initData = array(
            'id' => $city->id,
            'text' => $city->caption,
        );
        if ($city->area) {
            $initData['text'] .= ', '.$city->area->caption;
        }
        if ($city->region) {
            $initData['text'] .= ', '.$city->region->caption;
        }
        $initData = CJSON::encode($initData);
    }
}

echo $form->select2Group($model, $this->getModelCityField(), array(
    'widgetOptions' => array(
        'data' => array(),
        'asDropDownList' => false,
        'htmlOptions' => array(
            'id' => $this->id.'-city_id',
            'data-initvalue' => $initData,
        ),
    )
));

?>
<div class="form-group">
    <p class="col-sm-12 text-right">
        <?php echo CHtml::link(Yii::t('cse_city_selector', 'Open the address classifier'), '#', array(
            'id' => $this->id.'-address-classifier',
            'style' => (!$regionsStatus && !$areasStatus) ? 'display: none;' : '',
            'data-switch_text' => Yii::t('cse_city_selector', 'Close the address classifier')
        )); ?>
    </p>
</div>


<?php

echo CHtml::closeTag('div');

$cs = Yii::app()->getClientScript();
$cs->registerScript('TbSelect2#'.$this->id.'-country_id', false);
$cs->registerScript('TbSelect2#'.$this->id.'-region_id', false);
$cs->registerScript('TbSelect2#'.$this->id.'-area_id', false);
$cs->registerScript('TbSelect2#'.$this->id.'-city_id', false);


