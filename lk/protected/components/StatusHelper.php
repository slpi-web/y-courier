<?php

class StatusHelper
{

    const STATUS_DISABLED = 0;
    const STATUS_ENABLED = 1;

    public static function getLabels()
    {
        return array(
            self::STATUS_DISABLED => Yii::t('status_helper', 'value - disabled'),
            self::STATUS_ENABLED => Yii::t('status_helper', 'value - enabled'),
        );
    }

    public static function getLabel($value)
    {
        $labels = self::getLabels();
        if (isset($labels[$value]))
            return $labels[$value];

        return '';
    }

}