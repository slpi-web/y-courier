<?php
/**
 * @var $this ReportController
 * @var $model ReportForm
 */

$this->pageTitle = Yii::t('admin_pagetitle', 'Reports');

$this->breadcrumbs=array(
    Yii::t('admin_pagetitle', 'Reports'),
);

$modelName = 'ReportForm';

$form = $this->beginWidget(
    'booster.widgets.TbActiveForm',
        array(
            'id' => 'report-form',
            'type' => 'horizontal',
            'htmlOptions' => array(
            'class' => 'well',
            'enctype'=>'multipart/form-data',
        ),
    )
); ?>

<?php
echo $form->dropDownListGroup($model, 'report', array(
    'widgetOptions' => array(
        'data' => $model->getReportLabels(),
        'htmlOptions' => array(
        ),
    )
));
echo $form->radioButtonListGroup($model, 'period', array(
    'widgetOptions' => array(
        'data' => $model->getPeriodLabels(),
        'htmlOptions' => array(
        ),
    )
));
?>

<div id="form-month" style="<?php if ($model->period != ReportForm::PERIOD_MONTHLY) echo 'display:none;' ?>">
    <?php
    echo $form->datePickerGroup($model, 'startMonth',
        array(
            'label' => $model->getAttributeLabel('startMonth'),
            'labelOptions' => array(
                'required' => true,
            ),
            'widgetOptions' => array(
                'options' => array(
                    'format' => "mm.yyyy",
                    'startView' => "months",
                    'minViewMode' => "months"
                ),
            ),
            'prepend' => '<i class="glyphicon glyphicon-calendar"></i>'
        )
    );
    echo $form->datePickerGroup($model, 'endMonth',
        array(
            'label' => $model->getAttributeLabel('endMonth'),
            'labelOptions' => array(
                'required' => true,
            ),
            'widgetOptions' => array(
                'options' => array(
                    'format' => "mm.yyyy",
                    'startView' => "months",
                    'minViewMode' => "months"
                ),
            ),
            'prepend' => '<i class="glyphicon glyphicon-calendar"></i>'
        )
    );
    ?>
</div>
<div id="form-day" style="<?php if ($model->period != ReportForm::PERIOD_DAILY) echo 'display:none;' ?>">
    <?php
    echo $form->datePickerGroup($model, 'startDate',
        array(
            'label' => $model->getAttributeLabel('startDate'),
            'labelOptions' => array(
                'required' => true,
            ),
            'widgetOptions' => array(
                'options' => array(
                ),
            ),
            'prepend' => '<i class="glyphicon glyphicon-calendar"></i>'
        )
    );
    echo $form->datePickerGroup($model, 'endDate',
        array(
            'label' => $model->getAttributeLabel('endDate'),
            'labelOptions' => array(
                'required' => true,
            ),
            'widgetOptions' => array(
                'options' => array(
                ),
            ),
            'prepend' => '<i class="glyphicon glyphicon-calendar"></i>'
        )
    );
    ?>
</div>

<div id="form-cse-delivery-cash" style="<?php if ($model->report != ReportForm::REPORT_CSE_DELIVERY_MONEY) echo 'display: none;' ?>">
    <?php
    echo $form->radioButtonListGroup($model, 'cseDeliveryPaymentMethod', array(
        'label' => $model->getAttributeLabel('cseDeliveryPaymentMethod'),
        'labelOptions' => array(
            'required' => true,
        ),
        'widgetOptions' => array(
            'data' => $model->getCseDeliveryPaymentMethodLabels(),
            'htmlOptions' => array(
            ),
        )
    ));
    ?>
</div>

<?php
echo $form->radioButtonListGroup($model, 'format', array(
    'widgetOptions' => array(
        'data' => $model->getFormatLabels(),
        'htmlOptions' => array(
        ),
    )
));
?>

    <div class="text-center">
        <?php $this->widget(
            'ext.WLadda.TbLaddaButton',
            array('buttonType' => 'submit', 'label' => Yii::t('view_admin', 'Next'), 'htmlOptions' => array('class' => 'btn-primary'))
        ); ?>
    </div>

<?php $this->endWidget(); ?>

<?php
$cs = Yii::app()->getClientScript();
$cs->registerCoreScript('jquery');
$cs->registerScript('report-datepickers', '
    $("#'.$modelName.'_startMonth").on("changeDate", function (selected) {
        startDate = new Date(selected.date.valueOf());
        startDate.setDate(startDate.getDate(new Date(selected.date.valueOf())));
        $("#'.$modelName.'_endMonth").datepicker("setStartDate", startDate);
    });
    $("#'.$modelName.'_endMonth").on("changeDate", function (selected) {
        endDate = new Date(selected.date.valueOf());
        endDate.setDate(startDate.getDate(new Date(selected.date.valueOf())));
        $("#'.$modelName.'_startMonth").datepicker("setEndDate", endDate);
    });
    $("#'.$modelName.'_startDate").on("changeDate", function (selected) {
        startDate = new Date(selected.date.valueOf());
        startDate.setDate(startDate.getDate(new Date(selected.date.valueOf())));
        $("#'.$modelName.'_endDate").datepicker("setStartDate", startDate);
    });
    $("#'.$modelName.'_endDate").on("changeDate", function (selected) {
        endDate = new Date(selected.date.valueOf());
        endDate.setDate(startDate.getDate(new Date(selected.date.valueOf())));
        $("#'.$modelName.'_startDate").datepicker("setEndDate", endDate);
    });
');
$ids = array_keys($model->getPeriodLabels());
foreach ($ids as &$id) {
    $id = '#'.$modelName.'_period_'.$id;
}
$ids = implode(', ', $ids);
$cs->registerScript('report-select-period', '
    $("'.$ids.'").change(function(e){
        console.log("ch");
        if ($(this).val() == "'.ReportForm::PERIOD_DAILY.'") {
            $("#form-month").hide();
            $("#form-day").show();
        } else {
            $("#form-day").hide();
            $("#form-month").show();
        }
    });
');

$cs->registerScript('report-select-report', '
    $("#'.$modelName.'_report").change(function(e){
        if ($(this).val() == "'.ReportForm::REPORT_CSE_DELIVERY_MONEY.'") {
            $("#form-cse-delivery-cash").show();
        } else {
            $("#form-cse-delivery-cash").hide();
        }
    });
');
