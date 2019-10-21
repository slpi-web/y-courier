<?php
/**
 * @var $this AppealController
 * @var $model AppealAdmin
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
                'name' => 'id',
            ),
            array(
                'name' => 'user_id',
                'filter' => $this->widget('ext.OP.OPUserSelector', array(
                    'model' => $model,
                    'attribute' => 'user_id',
                    'ajaxUrl' => array('/admin/ajax/autocompleteUserClient'),
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
                    'ajaxUrl' => array('/admin/ajax/autocompleteBusinessCenter'),
                    'options' => array(
                        'allowClear' => true,
                        'placeholder' => ' ',
                    )
                ), true),
                'value' => '$data->businessCenter->caption',
            ),
            array(
                'name' => 'status',
                'filter' => $model->getStatusLabels(),
                'value' => '$data->getStatusLabel()',
            ),
            array(
                'name' => 'subject',
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
                'name' => 'appeal_departaments',
                'filter' => DataReceiver::getAppealDepartamentsList(),
                'value' => '$data->getDepartaments("<br>")',
                'type' => 'raw',
            ),
            array(
                'header' => Yii::t('view_admin', 'Actions'),
                'class' => 'booster.widgets.TbJsonButtonColumn',
                'template' => '{update} {delete}',
                'viewButtonUrl' => 'Yii::app()->createUrl("/admin/appeal/edit", array("id" => $data->id))',
                'updateButtonUrl' => 'Yii::app()->createUrl("/admin/appeal/edit", array("id" => $data->id))',
                'deleteButtonUrl' => 'Yii::app()->createUrl("/admin/appeal/delete", array("id" => $data->id))',
                'buttons' => array(
                )
            ),
        ),
    )
);