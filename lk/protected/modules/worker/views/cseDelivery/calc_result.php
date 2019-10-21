<?php
/**
 * @var $this CseDeliveryController
 * @var $cseDeliveryCalc CseDeliveryCalcForm
 * @var $cseDeliveryCalcResponse array
 */

$this->pageTitle = Yii::t('worker_pagetitle', 'Cse Delivery Calc Result');

$this->breadcrumbs=array(
    Yii::t('worker_pagetitle', 'Cse Delivery') => array('list'),
    Yii::t('worker_pagetitle', 'Cse Delivery Calc') => array('calc'),
    Yii::t('worker_pagetitle', 'Cse Delivery Calc Result'),
);
?>

<?php
if (isset($cseDeliveryCalcResponse['tariffs']) && is_array($cseDeliveryCalcResponse['tariffs']) && count($cseDeliveryCalcResponse['tariffs'])) {
    foreach ($cseDeliveryCalcResponse['tariffs'] as $tariff) {
        ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">
                    <?php echo $tariff['name']; ?>
                </h3>
            </div>
            <div class="panel-body">
                <p>
                    <?php
                    echo '<strong>'.$cseDeliveryCalc->getAttributeLabel('urgency_id').':</strong> '.$tariff['urgency'].'<br />';
                    echo '<strong>'.Yii::t('view_cse_calc', 'Minimum delivery time').':</strong> '.Yii::t('view_cse_calc', '{n} day|{n} days', $tariff['minPeriod']).'<br />';
                    echo '<strong>'.Yii::t('view_cse_calc', 'Maximum delivery time').':</strong> '.Yii::t('view_cse_calc', '{n} day|{n} days', $tariff['maxPeriod']).'<br />';
                    echo '<strong>'.Yii::t('view_cse_calc', 'Total').':</strong> '.$tariff['total'];
                    ?>
                </p>
            </div>
        </div>
        <?php
    }
} else {
    echo '<p>'.Yii::t('view_cse_calc', 'Can not calculate tariffs for your query.').'</p>';
}