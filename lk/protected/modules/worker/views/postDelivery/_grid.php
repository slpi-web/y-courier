<?php
/**
 * @var $this PostDeliveryController
 * @var $model PostDeliveryWorker
 */

$this->widget(
    'booster.widgets.TbJsonGridView',
    array(
        'dataProvider' => $model->search(),
        'filter' => $model,
        'type' => 'striped bordered condensed',
        'summaryText' => false,
        'cacheTTL' => 0,
        'cacheTTLType' => 's',
        'selectableRows' => 0,
        'columns' => array(
            array(
                'name' => 'user_id',
                'filter' => $this->widget('ext.OP.OPUserSelector', array(
                    'model' => $model,
                    'attribute' => 'user_id',
                    'ajaxUrl' => array('/worker/ajax/autocompleteUserClient'),
                    'options' => array(
                        'allowClear' => true,
                        'placeholder' => ' ',
                    )
                ), true),
                'value' => 'DataReceiver::getDisplayUserName($data->user)'
            ),
            array(
                'name' => 'business_center_id',
                'filter' => $this->widget('ext.OP.OPBusinessCenterSelector', array(
                    'model' => $model,
                    'attribute' => 'business_center_id',
                    'ajaxUrl' => array('/worker/ajax/autocompleteBusinessCenter'),
                    'options' => array(
                        'allowClear' => true,
                        'placeholder' => ' ',
                    )
                ), true),
                'value' => '$data->businessCenter->caption',
            ),
            array(
                'name' => 'office',
            ),
            array(
                'name' => 'timestamp',
                'headerHtmlOptions' => array(
                    'class' => 'w-20',
                ),
                'filter' => $this->widget('ext.WDateRangeFilter.WDateRangeFilter', array(
                    'model' => $model,
                    'attribute' => 'timestamp',
                ), true),
                'value' => 'Yii::app()->dateFormatter->formatDateTime($data->timestamp, "short", "short")'
            ),
            array(
                'name' => 'status',
                'filter' => $model->getStatusLabels(),
                'value' => '$data->getStatusLabel()',
            ),
            array(
                'header' => Yii::t('view_admin', 'Actions'),
                'class' => 'booster.widgets.TbJsonButtonColumn',
                'template' => '{update}',
                'viewButtonUrl' => 'Yii::app()->createUrl("/worker/postDelivery/edit", array("id" => $data->id))',
                'updateButtonUrl' => 'Yii::app()->createUrl("/worker/postDelivery/edit", array("id" => $data->id))',
                'buttons' => array(
                )
            ),
        ),
    )
);