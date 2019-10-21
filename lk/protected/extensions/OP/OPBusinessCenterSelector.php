<?php

class OPBusinessCenterSelector extends CInputWidget
{

    public $view = 'default';

    public $options = array();

    public $ajaxUrl = array('ajaxAutocompleteBusinessCenter');

    public function run()
    {
        $ajaxPageSize = isset(Yii::app()->params['autocompletePageSize']) ? Yii::app()->params['autocompletePageSize'] : 10;

        $this->options = CMap::mergeArray(array(
            'ajax' => array(
                'url' => CHtml::normalizeUrl($this->ajaxUrl),
                'dataType' => 'json',
                'data' => 'js:function (term, page) {
                    return {
                       query: term,
                       page: page
                    }
                }',
                'results' => 'js:function (data, page) {
                    var more = (page * '.$ajaxPageSize.') < data.total_count;
                    return {
                        results: data.items,
                        more: more
                    };
                }',
                'cache' => true,
            ),
            'allowClear' => false,
        ), $this->options);

        if ($this->model->{$this->attribute}) {
            $initOptions = 'js:function (element, callback) {
                    var data = element.data("initvalue");
                    if (data) {
                        if ($.isArray(data) && data[0]) {
                            data = data[0];
                        }
                        if (typeof data == "object") {
                            callback(data);
                        }
                    }
                }';




            $value = $this->model->{$this->attribute};
            if (!is_array($value))
                $value = array($value);

            $this->htmlOptions['value'] = implode(',', $value);

            $condition = new CDbCriteria();
            $condition->addInCondition('id', $value);
            $condition->select = 'id, caption';
            $businessCenters = BusinessCenter::model()->findAll($condition);
            $data = array();
            foreach ($businessCenters as $businessCenter) {
                $data[] = array(
                    'id' => $businessCenter->id,
                    'text' => $businessCenter->caption,
                );
            }

            if ((isset($this->options['multiple']) && $this->options['multiple']) || (isset($this->htmlOptions['multiple']) && $this->htmlOptions['multiple'])) {
                $initOptions = 'js:function (element, callback) {
                    var data = element.data("initvalue");
                    if (data) {
                        if ($.isArray(data)) {
                            callback(data);
                        }
                    }
                }';
            }
            $this->options = CMap::mergeArray(array(
                'initSelection' => $initOptions,
            ),$this->options);
            $this->htmlOptions['data-initvalue'] = CJSON::encode($data);

        }

        $this->render('businessCenter/'.$this->view);
    }

}