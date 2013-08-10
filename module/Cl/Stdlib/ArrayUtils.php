<?php
namespace Cl\Stdlib;

use Zend\Stdlib\ArrayUtils as ZendArrayUtils;
use Doctrine\Common\Collections\Collection;
/**
 * Description of ArrayUtils
 *
 * extends ArrayUtils from Zend and add Doctrine\Common\Collections\Collection detection as list
 *
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 * @copyright (c) 2013, Alexander Miertsch
 */
class ArrayUtils extends ZendArrayUtils
{
    static public function isList($value, $allowEmpty = false)
    {
        if ($value instanceof Collection) {
            if ($value->isEmpty()) {
                return $allowEmpty;
            } else {
                return true;
            }
        }

        return parent::isList($value, $allowEmpty);
    }
}
