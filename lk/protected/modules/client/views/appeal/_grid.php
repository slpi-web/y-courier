<?php
/**
 * @var $this AppealController
 * @var $model AppealClient
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
                'name' => 'subject',
            ),
            array(
                'name' => 'business_center_id',
                'filter' => DataReceiver::getBusinessCenterList(array(
                    'select' => array('id', 'caption'),
                    'scopes' => array('byUserId' => array($model->user_id)),
                )),
                'value' => '$data->businessCenter->caption',
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
                'name' => 'status',
                'filter' => $model->getStatusLabels(),
                'value' => '$data->getStatusLabel()',
            ),
            array(
                'header' => Yii::t('view_client', 'Actions'),
                'class' => 'booster.widgets.TbJsonButtonColumn',
                'template' => '{view}',
                'viewButtonUrl' => 'Yii::app()->createUrl("/client/appeal/view", array("id" => $data->id))',
                'buttons' => array(
                )
            ),
        ),
    )
);