<?php
/**
 * @var $this WModelHistory
 * @var $model ModelHistory
 * @var $dataProvider CActiveDataProvider
 */

$this->widget(
    'booster.widgets.TbJsonGridView',
    array(
        'dataProvider' => $dataProvider,
        'filter' => $model,
        'type' => 'striped bordered condensed',
        'summaryText' => false,
        'cacheTTL' => 0,
        'cacheTTLType' => 's',
        'selectableRows' => 0,
        'columns' => array(
            array(
                'name' => 'user_id',
                'filter' => $this->userAutocompleteAjaxUrl ? $this->widget('ext.OP.OPUserSelector', array(
                    'model' => $model,
                    'attribute' => 'user_id',
                    'ajaxUrl' => $this->userAutocompleteAjaxUrl,
                    'options' => array(
                        'allowClear' => true,
                        'placeholder' => ' ',
                    )
                ), true) : false,
                'value' => '$data->user ? DataReceiver::getDisplayUserName($data->user) : ""',
            ),
            array(
                'name' => 'timestamp',
                'filter' => $this->widget('ext.WDateRangeFilter.WDateRangeFilter', array(
                    'model' => $model,
                    'attribute' => 'timestamp',
                    'options' => array(
                        'opens' => 'center',
                    ),
                ), true),
                'value' => 'Yii::app()->dateFormatter->formatDateTime($data->timestamp, "short", "short")'
            ),
            array(
                'name' => 'fields',
                'filter' => $model->getFieldsLabels(),
                'type' => 'raw',
                'value' => '$this->grid->owner->renderFields($data)',
            ),
        ),
    )
);