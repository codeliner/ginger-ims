<?php
namespace Cl\Stdlib;
/**
 * von Weth Online Shop
 *
 * @link      http://vonwerth.de/
 * @copyright Copyright (c) 2012 von Werth GmbH
 */


class AbstractDataObject
{
    public function exchangeArray($data)
    {
        if (!is_array($data) && !$data instanceof \Traversable) {
            throw new Exception\InvalidArgumentException(
                "Data must be an array or instance of Traversable"
                . " but type of "
                . ((is_object($list))? get_class($list) : gettype($list))
                . " given."
            );
        }

       $this->checkData($data);

        foreach ($data as $key => $value) {
            $setterName = $this->inflectSetterNameFromKey($key);

            $this->{$setterName}($value);
        }
    }

    public function toArray()
    {
        $methods = get_class_methods(get_class($this));
        $getterMethods = array();

        foreach ($methods as $method) {
            if (strpos($method, 'get') === 0)
                $getterMethods[] = $method;
        }

        $data = array();

        foreach ($getterMethods as $method) {
            $key = preg_replace('/^get/', '', $method);
            $key = lcfirst($key);
            if (preg_match_all('/[A-Z]/', $key, $matches)) {
                $ucLetters = $matches[0];

                foreach ($ucLetters as $ucLetter) {
                    $lcLetter = strtolower($ucLetter);
                    $key = str_replace($ucLetter, '_' . $lcLetter, $key);
                }
            }

            $data[$key] = $this->{$method}();
        }

        return $data;
    }

    protected function checkData($data)
    {
        $methods = get_class_methods(get_class($this));
        $countGetter = 0;

        foreach ($methods as $method) {
            if (strpos($method, 'set') === 0)
                $countSetter++;
        }

        if ($countSetter != count($data)) {
            throw new Exception\InvalidArgumentException(
                'Data contains a wrong number of keys.'
                . ' Expected: ' . $countSetter
                . ' Found: ' . count($data)
                . "\n" . print_r($data, true)
            );
        }

        return $data;
    }

    protected function inflectSetterNameFromKey($key)
    {
        $parts = explode('_', $key);

        $funcName = 'set';

        foreach ($parts as $part) {
            $funcName.= ucfirst($part);
        }

        return $funcName;
    }
}
