<?php

Yii::import('ext.ModelHistory.models.*');
Yii::import('ext.ModelHistory.models.base.*');

class ModelHistoryBehavior extends CActiveRecordBehavior
{

    public $modelName = null;
    public $saveUser = true;

    /**
     * @var array
     *
     * array(
     *  'attribute' => 'eval code',
     * )
     */
    public $attributes = null;

    protected $oldAttributes = null;

    protected function getModelPk()
    {
        return $this->owner->getPrimaryKey();
    }

    protected function getModelAttributes()
    {
        if (isset($this->attributes)) {
            $attributes = array();
            foreach ($this->attributes as $key => $value) {
                $attributes[$key] = $this->owner->{$key};
            }
        } else {
            $attributes = $this->owner->getAttributes();
        }
        return $attributes;
    }

    protected function getUserId()
    {
        $id = Yii::app()->user->getId();
        if ($id)
            return $id;
        else
            return null;
    }

    protected function afterConstruct($event)
    {
        if (!$this->modelName)
            $this->modelName = get_class($this->owner);
    }

    protected function getSearchFieldsLabels()
    {
        $labels = $this->owner->attributeLabels();
        if (is_array($this->attributes)) {
            foreach ($labels as $key => $value) {
                if (!isset($this->attributes[$key]))
                    unset($labels[$key]);
            }
        }
        return $labels;
    }

    public function afterDelete($event)
    {
        ModelHistory::model()->deleteAllByAttributes(array(
            'model' => $this->modelName,
            'id' => $this->getModelPk(),
        ));
    }

    public function afterSave($event)
    {
        $attributes = $this->getChangedModelAttributes();
        if (!empty($attributes)) {
            $model = new ModelHistory();
            $model->model = $this->modelName;
            $model->id = $this->getModelPk();
            if ($this->saveUser)
                $model->user_id = $this->getUserId();
            $model->setFields($attributes);
            if ($this->owner->getIsNewRecord()) {
                $model->deleteAllByAttributes(array(
                    'model' => $this->modelName,
                    'id' => $this->getModelPk(),
                ));
            }
            $model->save(false);
        }
    }

    public function afterFind($event)
    {
        if (!$this->owner->getIsNewRecord())
            $this->oldAttributes = $this->getModelAttributes();
    }

    public function getChangedModelAttributes()
    {
        $attributes = $this->getModelAttributes();
        if (is_array($this->attributes)) {
            $controlAttributes = array_keys($this->attributes);
            foreach ($attributes as $key => $value) {
                if (!in_array($key, $controlAttributes))
                    unset($attributes['key']);
            }
        }
        if (is_array($this->oldAttributes)) {
            foreach ($attributes as $key => $value) {
                if (isset($this->oldAttributes[$key])) {
                    if ($value == $this->oldAttributes[$key])
                        unset($attributes[$key]);
                }
            }
        }
        return $attributes;
    }

    public function getMHDataProvider($model = null)
    {
        if (!$model || !($model instanceof ModelHistory))
            $model = new ModelHistory();

        $model->model = $this->modelName;
        $model->id = $this->getModelPk();

        return $model->search();
    }

    public function getMHSearchModel()
    {
        $model = new ModelHistory('search');
        $model->setFieldsLabels($this->getSearchFieldsLabels());
        return $model;
    }

    public function getMHAttributesConfig()
    {
        if (is_array($this->attributes))
            return $this->attributes;
        else {
            $config = $this->getModelAttributes();
            foreach ($config as $key => &$value) {
                $value = true;
            }
            return $config;
        }
    }



}