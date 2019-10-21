<?php
/**
 * @var $this WDateRangeFilter
 */

$id = $this->getId();
?>

<div class="input-group">

    <?php
    $this->widget('booster.widgets.TbDateRangePicker', array(
        'model' => $this->model,
        'attribute' => $this->attribute,
        'options' => $this->options,
        'events' => array(
            'cancel.daterangepicker' => 'js:function(ev, picker) { $(this).val(""); }',
        ),
        'initValue' => '',
        'htmlOptions' => array(
            'style' => 'width: auto; min-width: 0;',
        )
    ));
    ?>

    <span class="input-group-btn">
        <?php echo CHtml::htmlButton('<i class=" glyphicon glyphicon-remove-circle"></i>', array(
            'id' => $id,
            'class' => 'btn btn-default',
        )); ?>
      </span>
</div>

<?php
$cs = Yii::app()->getClientScript();
$cs->registerCoreScript('jquery');
$cs->registerScript('WDateRangeFilter#'.$id, '$("#'.$id.'").click(function(e){var element = $(this).parent().prev(); var val = element.val(); element.val(""); if (val != "") element.trigger("change"); });');
