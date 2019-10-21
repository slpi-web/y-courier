<?php
/**
 * @var $this CseDeliveryController
 * @var $model CseDeliveryAdmin
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
                    'ajaxUrl' => array('/admin/ajax/autocompleteUserClient'),
                    'options' => array(
                        'allowClear' => true,
                        'placeholder' => ' ',
                    )
                ), true),
                'value' => 'DataReceiver::getDisplayUserName($data->user)'
            ),
            array(
                'name' => 'sender',
            ),
            array(
                'name' => 'sender_city',
                'value' => '$data->senderCity->caption',
            ),
            array(
                'name' => 'recipient',
            ),
            array(
                'name' => 'recipient_city',
                'value' => '$data->recipientCity->caption',
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
                'name' => 'client_status',
                'filter' => $model->getClientStatusLabels(),
                'value' => '$data->getClientStatusLabel()'
            ),
            array(
                'name' => 'status',
                'filter' => $model->getStatusLabels(),
                'value' => '$data->getStatusLabel()',
            ),
            array(
                'header' => Yii::t('view_admin', 'Actions'),
                'class' => 'booster.widgets.TbJsonButtonColumn',
                'template' => '{view} {update} {delete}',
                'viewButtonUrl' => 'Yii::app()->createUrl("/admin/cseDelivery/getWaybill", array("id" => $data->id))',
                'updateButtonUrl' => 'Yii::app()->createUrl("/admin/cseDelivery/edit", array("id" => $data->id))',
                'deleteButtonUrl' => 'Yii::app()->createUrl("/admin/cseDelivery/delete", array("id" => $data->id))',
                'buttons' => array(
                    'view' => array(
                        'label' => Yii::t('view_cse_form', 'download waybill'),
                        'visible' => '$data->isWaybillAvailable()',
                    )
                )
            ),
        ),
    )
);