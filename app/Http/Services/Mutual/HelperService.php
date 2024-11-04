<?php

namespace App\Http\Services\Mutual;

class HelperService
{
    public function safeArray($array) {
        foreach ($array as &$value) {
            if (is_array($value)) {
                $value = $this->safeArray($value);
            } elseif (is_null($value)) {
                $value = '';
            }
        }
        unset($value);
        return $array;
    }

    public function safeJson($json) {
        $result = [];
        foreach ($json as $key => $value) {
            if (is_array($value) && count(array_filter(array_keys($value), 'is_numeric')) === count($value)) {
                $result[$key] = array_values($value);
            } else {
                $result[$key] = is_array($value) ? $this->safeJson($value) : $value;
            }
        }
        return $result;
    }
}
