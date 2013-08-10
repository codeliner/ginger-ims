<?php
namespace Cl\Filter;

use Zend\Filter\AbstractFilter;
use Cl\Stdlib\Formatter;
/**
 * Description of Url
 *
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 * @copyright (c) 2013, Alexander Miertsch
 */
class Url extends AbstractFilter
{
    public function filter($value)
    {
        return Formatter::urlClean($value);
    }
}