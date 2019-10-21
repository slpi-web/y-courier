<?php
/**
 * @var $this CseDeliveryController
 * @var $model CseAddressBook
 */

$this->widget(
    'booster.widgets.TbJsonGridView',
    array(
        'id' => 'addressbook-grid',
        'dataProvider' => $model->search(),
        'filter' => $model,
        'type' => 'striped bordered condensed',
        'summaryText' => false,
        'cacheTTL' => 0,
        'cacheTTLType' => 's',
        'selectableRows' => 0,
        'columns' => array(
            array(
                'name' => 'type',
                'filter' => $model->getTypeLabels(),
            ),
            array(
                'name' => 'name',
            ),
            array(
                'name' => 'city',
                'value' => '$data->cityModel->caption',
            ),
            array(
                'name' => 'address'
            ),
            array(
                'name' => 'phone'
            ),
            array(
                'header' => Yii::t('view_admin', 'Actions'),
                'class' => 'booster.widgets.TbJsonButtonColumn',
                'template' => '{use} {delete}',
                'buttons' => array(
                    'use' => array(
                        'label' => Yii::t('view_addressbook', 'Use this'),
                        'icon' => 'ok',
                        'url' => '"#"',
                        'options' => array(
                            'class' => 'use',
                            'data-formdata' => '$data->getFormDataJson()',
                        ),
                        'evaluateOptions' => array('data-formdata'),
                    ),
                    'delete' => array(
                        'url' => 'CHtml::normalizeUrl(array("/worker/cseDelivery/addressbook", "action" => "delete", "id" => "$data->id"));',
                    )
                )
            ),
        ),
    )
);