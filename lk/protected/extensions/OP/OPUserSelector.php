<?php

class OPUserSelector extends CInputWidget
{

    public $view = 'default';

    public $options = array();

    public $ajaxUrl = array('ajaxAutocompleteUser');

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
            $this->options = CMap::mergeArray(array(
                'initSelection' => 'js:function (element, callback) {
                    var data = element.data("initvalue");
                    if (data) {
                        if (typeof data == "object") {
                            callback(data);
                        }
                    }
                }',
            ),$this->options);

            $this->htmlOptions['data-initvalue'] = CJSON::encode(array(
                'id' => $this->model->{$this->attribute},
                'text' => $this->model->user ? DataReceiver::getDisplayUserName($this->model->user) : '',
            ));
        }

        $this->render('userSelector/'.$this->view);
    }

}