<?php

namespace Lllist;

class Utils
{
    /**
     * Returns the first [$index, $item] of a predicate
     *
     * @param array $arr
     * @param callable $predicate
     * @return array [$index, $value]
     */
    public static function indexOf(array $arr, callable $predicate)
    {
        foreach ($arr as $i => $v) {
            if ($predicate($v)) {
                return [$i, $v];
            }
        }

        return null;
    }

    /**
     * Returns the last [$index, $item] of a predicate
     *
     * @param array $arr
     * @param callable $predicate
     * @return array
     */
    public static function lastIndexOf(array $arr, callable $predicate)
    {
        return self::indexOf(array_reverse($arr, true), $predicate);
    }

    /**
     * Check if value is not empty, if `$strict` is `true` will
     * use PHP's `empty` if `false` will use `is_null and !== ""`
     *
     * @param $value
     * @param bool $strict
     * @return bool
     */
    public static function notEmpty($value, $strict = true)
    {
        if (!$strict && !is_array($value)) {
            return !is_null($value) && trim($value) !== "";
        } else {
            return !empty($value);
        }
    }

    /**
     * Check if value is not empty relaxed(using is_null and !== "")
     *
     * @param $value
     * @return bool
     */
    public static function relaxedNotEmpty($value)
    {
        return self::notEmpty($value, false);
    }

    /**
     * Returns first element of array
     *
     * @param array $arr
     * @return mixed
     */
    public static function head(array $arr) {
        return reset($arr);
    }

    /**
     * Returns last element of array
     *
     * @param array $arr
     * @return mixed
     */
    public static function last(array $arr) {
        return end($arr);
    }
}