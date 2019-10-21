<?php

class WModelHistory extends CWidget
{

    public $model;
    public $view = 'default';
    public $userAutocompleteAjaxUrl = false;

    public $iframed = false;

    protected $attributesConfig = array();
    protected $modelClass = '';
    protected $baseUrl = null;

    public function init()
    {
        if (!is_subclass_of($this->model, 'CActiveRecord'))
            throw new CHttpException('505', Yii::t('model_history_widget', 'Model must be CActiveRecord instance with enabled ModelHistory behavior.'));

        $this->modelClass = get_class($this->model);
        if ($this->iframed) {
            $dir = dirname(__FILE__) . DIRECTORY_SEPARATOR;
            $this->baseUrl = Yii::app()->getAssetManager()->publish($dir . 'assets', false, -1, YII_DEBUG);
        }
    }

    public function run()
    {
        if ($this->iframed) {
            $cs = Yii::app()->getClientScript();
            $cs->registerCoreScript('jquery');
            if (YII_DEBUG)
                $cs->registerScriptFile($this->baseUrl . '/js/iframeResizer.contentWindow.js', CClientScript::POS_HEAD);
            else
                $cs->registerScriptFile($this->baseUrl . '/js/iframeResizer.contentWindow.min.js', CClientScript::POS_HEAD);
        }

        $model = $this->model->getMHSearchModel();

        $params = Yii::app()->request->getQuery(get_class($model));
        if (is_array($params))
            $model->attributes = $params;

        $this->attributesConfig = $this->buildFullAttributesConfig();

        $this->render($this->view, array(
            'model' => $model,
            'dataProvider' => $this->model->getMHDataProvider($model),
        ));
    }

    /**
     * @param ModelHistory $data
     * @return string
     */
    public function renderFields(ModelHistory $data)
    {
        $fields = $data->getFields();

        return $this->render($this->view.'_fields', array(
            'data' => $data,
            'fields' => $fields,
            'attributes' => $this->buildAttributesConfig($fields),
        ), true);
    }


    protected function buildFullAttributesConfig()
    {
        $attributeLabels = $this->model->attributeLabels();
        $attributesConfig = $this->model->getMHAttributesConfig();

        $result = array();
        foreach ($attributesConfig as $attribute => $value) {
            $config = array(
                'label' => isset($attributeLabels[$attribute]) ? $attributeLabels[$attribute] : $attribute,
                'name' => $attribute,
                'type' => 'raw',
            );
            if ($value !== true) {
                $config['value'] = $value;
            }
            $result[$attribute] = $config;
        }
        return $result;
    }

    protected function buildAttributesConfig($fields)
    {
        $model = $this->getRenderModel($fields);

        $result = array();
        foreach ($this->attributesConfig as $key => $value) {
            if (isset($fields[$key])) {
                if (isset($value['value'])) {
                    $value['value'] = $this->evaluateExpression($value['value'], array(
                        'data' => $fields[$key],
                        'model' => $model,
                    ));
                }
                $result[] = $value;
            }
        }
        return $result;
    }

    protected function getRenderModel($fields)
    {
        $model = new $this->modelClass;
        foreach ($fields as $key => $value) {
            $model->{$key} = $value;
        }
        return $model;
    }

}