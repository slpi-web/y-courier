<?php
/**
 * @var $this OPBusinessCenterSelector
 */

$this->widget('booster.widgets.TbSelect2', array(
    'model' => $this->model,
    'attribute' => $this->attribute,
    'options' => $this->options,
    'data' => array(),
    'asDropDownList' => false,
    'htmlOptions' => $this->htmlOptions,
));