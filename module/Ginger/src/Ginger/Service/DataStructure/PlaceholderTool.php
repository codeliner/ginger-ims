<?php
namespace Ginger\Service\DataStructure;
/**
 * Description of PlaceholderTool
 *
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 * @copyright (c) 2013, Alexander Miertsch
 */
class PlaceholderTool
{
    static public function readPlaceholderKeys($str)
    {
        $placeholders = array();

        preg_match_all('/\[(?P<placeholder>[^\]]+)\]/', $str, $placeholders);

        return $placeholders['placeholder'];
    }
    static public function replace($str, array $placeholderMap)
    {
        $placeholders = array_map(function($key) {
            return '[' . $key . ']';
        }, array_keys($placeholderMap));

        $replace = array_values($placeholderMap);

        return str_replace($placeholders, $replace, $str);
    }
}