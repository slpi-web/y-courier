<?php

/**
 * Created by PhpStorm.
 * User: dim
 * Date: 11.11.2015
 * Time: 11:26
 */
class DataParser
{

    public static function parseEmailList($text)
    {
        $emails = explode("\n", $text);
        foreach ($emails as $key => $val) {
            if (!trim($val))
                unset($emails[$key]);
        }
        return $emails;
    }

}