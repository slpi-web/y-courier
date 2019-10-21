<?php
/**
 * @var $this AppealController
 * @var $model AppealDepartamentAdmin
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
                'name' => 'caption',
            ),
            array(
                'name' => 'status',
                'filter' => $model->getStatusLabels(),
                'value' => '$data->getStatusLabel()',
            ),
            array(
                'header' => Yii::t('view_admin', 'Actions'),
                'class' => 'booster.widgets.TbJsonButtonColumn',
                'template' => '{update} {delete}',
                'viewButtonUrl' => 'Yii::app()->createUrl("/admin/appeal/departamentEdit", array("id" => $data->id))',
                'updateButtonUrl' => 'Yii::app()->createUrl("/admin/appeal/departamentEdit", array("id" => $data->id))',
                'deleteButtonUrl' => 'Yii::app()->createUrl("/admin/appeal/departamentDelete", array("id" => $data->id))',
                'buttons' => array(
                )
            ),
        ),
    )
);