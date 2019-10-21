<?php
/**
 * @var $this BusinessCenterController
 * @var $model BusinessCenterAdmin
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
                'name' => 'address',
            ),
            array(
                'name' => 'phone',
            ),
            array(
                'header' => Yii::t('view_admin', 'Actions'),
                'class' => 'booster.widgets.TbJsonButtonColumn',
                'template' => '{update} {delete}',
                'viewButtonUrl' => 'Yii::app()->createUrl("/admin/businessCenter/edit", array("id" => $data->id))',
                'updateButtonUrl' => 'Yii::app()->createUrl("/admin/businessCenter/edit", array("id" => $data->id))',
                'deleteButtonUrl' => 'Yii::app()->createUrl("/admin/businessCenter/delete", array("id" => $data->id))',
                'buttons' => array(
                )
            ),
        ),
    )
);