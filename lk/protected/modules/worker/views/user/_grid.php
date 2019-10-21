<?php
/**
 * @var $this UserController
 * @var $model UserWorker
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
                'name' => 'email',
            ),
            array(
                'name' => 'display',
            ),
            array(
                'name' => 'status',
                'filter' => $model->getStatusLabels(),
                'value' => '$data->getStatusLabel()',
            ),
            array(
                'name' => 'create_time',
                'filter' => false,
                'value' => 'Yii::app()->dateFormatter->formatDatetime($data->create_time, "short", "short")',
            ),
            array(
                'name' => 'last_login_time',
                'filter' => false,
                'value' => 'Yii::app()->dateFormatter->formatDatetime($data->last_login_time, "short", "short")',
            ),
            array(
                'header' => Yii::t('view_admin', 'Actions'),
                'class' => 'booster.widgets.TbJsonButtonColumn',
                'template' => '{update}',
                'viewButtonUrl' => '',
                'updateButtonUrl' => 'Yii::app()->createUrl("/worker/user/edit", array("id" => $data->id))',
                'deleteButtonUrl' => '',
                'buttons' => array(
                )
            ),
        ),
    )
);