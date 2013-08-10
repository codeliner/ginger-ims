<?php

/**
 * Stdlib Formatter
 *
 * @author Alexander Miertsch kontakt@codeliner.ws
 * @package Cl
 * @subpackage Stdlib
 * @version 1.0
 */
namespace Cl\Stdlib;

class Formatter {
    public static function tagStr2Array($str, $trenner = ",")
    {
        $arr = explode($trenner, $str);
        if (is_array($arr))
        {
            $ret = array();

            foreach ($arr as $value)
            {
                $value = trim($value);
                if ($value != "")
                    $ret[] = $value;
            }

            return $ret;
        }
        else return array();
    }

    public static function array2TagStr (array $array) {
        if (count($array)) {
            return implode(', ', $array);
        } else return '';
    }

    public static function urlClean ($str) {
        $searchArr = array(
            'Ä',
            'ä',
            'Ü',
            'ü',
            'Ö',
            'ö',
            'ß',
        );

        $replaceArr = array(
            'Ae',
            'ae',
            'Ue',
            'ue',
            'Oe',
            'oe',
            'ss',
        );
        $str = str_replace($searchArr, $replaceArr, $str);

        $str = preg_replace('/[^a-zA-Z0-9-_]/', '-', $str);

        $str = preg_replace('/[\-]+/', '-', $str);

        return trim($str, '-');
    }

    public static function timestampToDate($ts) {
        return date('d.m.Y', $ts);
    }

    public static function timestampToTime($ts, $withSeconds = true) {
        $formatStr = 'H:i' . (($withSeconds)? ':s' : '');

        return date($formatStr, $ts);
    }

    public static function timestampToDateTime($ts, $withSeconds = true) {
        return static::timestampToDate($ts) . ' ' . static::timestampToTime($ts, $withSeconds);
    }

    public static function normalizeName($name)
    {
        return str_replace(' ', '', mb_strtolower($name, 'utf-8'));
    }

    public static function camleCaseToSnakeCase($mixed) {
        if (is_string($mixed)) {
            return strtolower(preg_replace('/([a-z])([A-Z])/', '$1_$2', $mixed));
        } else if (is_array($mixed)) {
            foreach (array_keys($mixed) as $key):
                // Working with references here to avoid copying the value
                $value = $mixed[$key];
                unset($mixed[$key]);
                //  - camelCase to snake_case
                $transformedKey = self::camleCaseToSnakeCase($key);
                 // Work recursively
                if (is_array($value)) $value = self::camleCaseToSnakeCase($value);
                // Store with new key
                $mixed[$transformedKey] = $value;

                unset($value);
            endforeach;

            return $mixed;
        }

        return $mixed;
    }
}
