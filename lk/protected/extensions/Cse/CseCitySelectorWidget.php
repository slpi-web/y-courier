<?php

class CseCitySelectorWidget extends CWidget
{

    public $form;

    public $model;

    public $type = 'sender';

    public $view = 'default';

    public $ajaxCountryInfoUrl = '';

    public $ajaxRegionAutocompleteUrl = '';

    public $ajaxAreaAutocompleteUrl = '';

    public $ajaxCityAutocompleteUrl = '';

    protected $baseUrl;

    protected $options= array();

    protected $classifierModel;

    public function init()
    {
        parent::init();

        Yii::import('ext.Cse.models.*');

        $this->id = $this->getId();

        $dir = dirname(__FILE__) . DIRECTORY_SEPARATOR;
        $this->baseUrl = Yii::app()->getAssetManager()->publish($dir . 'assets', false, -1, YII_DEBUG);
    }

    public function run()
    {

        $clientScript = Yii::app()->getClientScript();
        $clientScript->registerCoreScript('jquery');

        $this->classifierModel = new CseAddressClassifierForm();

        $cityModel = false;
        if (isset($this->model->{$this->type.'_city'}) && $this->model->{$this->type.'_city'}) {
            $cityModel = CseCity::model()->typeLocality()->findByPk($this->model->{$this->type.'_city'});
            if ($cityModel) {
                $this->classifierModel->country_id = $cityModel->country_id;
            }
        }

        if (YII_DEBUG)
            $clientScript->registerScriptFile($this->baseUrl . '/js/cse-city-selector.jquery.js', CClientScript::POS_HEAD);
        else
            $clientScript->registerScriptFile($this->baseUrl . '/js/cse-city-selector.jquery.js', CClientScript::POS_HEAD);

        $this->options = array(
            'ajaxCountryInfoUrl' => is_array($this->ajaxCountryInfoUrl) ? CHtml::normalizeUrl($this->ajaxCountryInfoUrl) : $this->ajaxCountryInfoUrl,
            'ajaxRegionAutocompleteUrl' => is_array($this->ajaxRegionAutocompleteUrl) ? CHtml::normalizeUrl($this->ajaxRegionAutocompleteUrl) : $this->ajaxRegionAutocompleteUrl,
            'ajaxAreaAutocompleteUrl' => is_array($this->ajaxAreaAutocompleteUrl) ? CHtml::normalizeUrl($this->ajaxAreaAutocompleteUrl) : $this->ajaxAreaAutocompleteUrl,
            'ajaxCityAutocompleteUrl' => is_array($this->ajaxCityAutocompleteUrl) ? CHtml::normalizeUrl($this->ajaxCityAutocompleteUrl) : $this->ajaxCityAutocompleteUrl,
            'autocompletePageSize' => isset(Yii::app()->params['autocompletePageSize']) ? Yii::app()->params['autocompletePageSize'] : 10
        );

        $clientScript->registerScript('cse_city_selector-'.$this->id, '$("#'.$this->id.'").cseCitySelector('.CJavaScript::jsonEncode($this->options).');', CClientScript::POS_READY);

        echo $this->renderHtml();
    }

    public function renderHtml()
    {
        $this->render('citySelector/'.$this->view, array(
            'form' => $this->form,
            'model' => $this->model,
            'classifierModel' => $this->classifierModel,
            'type' => $this->type,
        ));
    }

    public function getModelCityField()
    {
        if ($this->type == '')
            return 'city';
        else
            return $this->type.'_city';
    }

}