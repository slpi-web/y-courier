<?php

class ReportForm extends CFormModel
{
    const REPORT_CSE_DELIVERY_COUNT = 0;
    const REPORT_CSE_DELIVERY_MONEY = 1;
    const REPORT_POST_DELIVERY = 2;
    const REPORT_APPEAL = 3;

    const PERIOD_MONTHLY = 0;
    const PERIOD_DAILY = 1;

    const CSE_DELIVERY_PAYMENT_METHOD_CASHLESS = 0;
    const CSE_DELIVERY_PAYMENT_METHOD_CASH = 1;

    public $report;
    public $period;

    public $startDate;
    public $endDate;

    public $startMonth;
    public $endMonth;

    public $cseDeliveryPaymentMethod;

    public $format;

    const FORMAT_CSV_WIN = 0;
    const FORMAT_CSV_NIX = 1;
    const FORMAT_MS_EXCEL = 2;

    public function rules()
    {
        $rules = array(
            array('report, period, format', 'required'),
            array('report', 'in', 'range' => array_keys($this->getReportLabels())),
            array('period', 'in', 'range' => array_keys($this->getPeriodLabels())),
            array('format', 'in', 'range' => array_keys($this->getFormatLabels())),
        );

        if ($this->period == self::PERIOD_DAILY) {
            $rules = CMap::mergeArray($rules, array(
                array('startDate, endDate', 'required'),
                array('startDate, endDate', 'date', 'format' => 'dd.MM.yyyy'),
            ));
        } else {
            $rules = CMap::mergeArray($rules, array(
                array('startMonth, endMonth', 'required'),
                array('startMonth, endMonth', 'date', 'format' => 'MM.yyyy'),
            ));
        }

        if ($this->report == self::REPORT_CSE_DELIVERY_MONEY) {
            $rules = CMap::mergeArray($rules, array(
                array('cseDeliveryPaymentMethod', 'required'),
                array('cseDeliveryPaymentMethod', 'in', 'range' => array_keys($this->getCseDeliveryPaymentMethodLabels())),
            ));
        }

        return $rules;
    }

    public function behaviors()
    {
        return array(
            'preset' => array(
                'class' => 'ext.ActiveRecord.PresetAttributesActiveRecordBehavior',
                'safePresetAttributes' => array(
                    'report', 'period',
                ),
            )
        );
    }

    public function attributeLabels()
    {
        return array(
            'report' => Yii::t('model_reportForm', 'report'),
            'period' => Yii::t('model_reportForm', 'period'),
            'format' => Yii::t('model_reportForm', 'format'),
            'startDate' => Yii::t('model_reportForm', 'startDate'),
            'endDate' => Yii::t('model_reportForm', 'endDate'),
            'startMonth' => Yii::t('model_reportForm', 'startMonth'),
            'endMonth' => Yii::t('model_reportForm', 'endMonth'),
            'cseDeliveryPaymentMethod' => Yii::t('model_reportForm', 'payment method'),
        );
    }

    public function init()
    {
        $this->period = self::PERIOD_MONTHLY;
        $this->cseDeliveryPaymentMethod = self::CSE_DELIVERY_PAYMENT_METHOD_CASHLESS;
        $this->format = self::FORMAT_CSV_WIN;
    }

    public function getReportLabels()
    {
        return array(
            self::REPORT_CSE_DELIVERY_COUNT => Yii::t('model_reportForm', 'report - cse delivery count'),
            self::REPORT_CSE_DELIVERY_MONEY => Yii::t('model_reportForm', 'report - cse delivery money'),
            self::REPORT_POST_DELIVERY => Yii::t('model_reportForm', 'report - post delivery'),
            self::REPORT_APPEAL => Yii::t('model_reportForm', 'report - appeal'),
        );
    }

    public function getPeriodLabels()
    {
        return array(
            self::PERIOD_MONTHLY => Yii::t('model_reportForm', 'period - monthly'),
            self::PERIOD_DAILY => Yii::t('model_reportForm', 'period - daily'),
        );
    }

    public function getCseDeliveryPaymentMethodLabels()
    {
        return array(
            self::CSE_DELIVERY_PAYMENT_METHOD_CASHLESS => Yii::t('model_reportForm', 'cse delivery payment method - cashless'),
            self::CSE_DELIVERY_PAYMENT_METHOD_CASH => Yii::t('model_reportForm', 'cse delivery payment method - cash'),
        );
    }

    public function getFormatLabels()
    {
        return array(
            self::FORMAT_CSV_WIN => Yii::t('model_reportForm', 'CSV for ms excel'),
            self::FORMAT_CSV_NIX => Yii::t('model_reportForm', 'CSV'),
            //self::FORMAT_MS_EXCEL => Yii::t('model_reportForm', 'MS Excel'),
        );
    }



}