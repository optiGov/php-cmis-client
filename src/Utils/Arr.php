<?php

namespace CMIS\Utils;

class Arr
{
    /**
     * Returns a value from an array by key supporting dot notation.
     *
     * @param array $array
     * @param string $key
     * @param mixed $default
     * @return mixed|null
     */
    static function get(array $array, string $key, mixed $default = null): mixed
    {
        // if the key exists, return the value
        if (array_key_exists($key, $array)) {
            return $array[$key];
        }

        // if the key does not exist, check for dot notation
        $keys = explode(".", $key);
        $value = $array;

        // loop through the keys and check if the value exists
        foreach ($keys as $key) {
            if (array_key_exists($key, $value)) {
                $value = $value[$key];
            } else {
                return $default;
            }
        }

        // return the value
        return $value;
    }
}