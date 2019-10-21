<?php

class YesNoHelper
{

    const VALUE_NO = 0;
    const VALUE_YES = 1;

    public static function getLabels()
    {
        return array(
            self::VALUE_NO => Yii::t('yes_no_helper', 'value - no'),
            self::VALUE_YES => Yii::t('yes_no_helper', 'value - yes'),
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