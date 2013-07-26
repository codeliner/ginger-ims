<?php
namespace MockObject;

use Ginger\Model\Feature\AbstractFeature;
use Ginger\Model\Connector\ConnectorEvent;
/**
 * Description of ValueChangeFeature
 *
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 * @copyright (c) 2013, Alexander Miertsch
 */
class ValueChangeFeature extends AbstractFeature
{
    public function alterValue($value, $attributeToAlter, ConnectorEvent $e)
    {
        if (is_string($value)) {
            return $value.'-changed';
        } else if (is_array($value)) {
            if (isset($value['name'])) {
                $value['name'] .= '-changed';
            } else {
                foreach($value as $i => $valueEl) {
                    $value[$i] = $valueEl.'-changed';
                }
            }
        }

        return $value;
    }

    public function getType()
    {
        return static::TYPE_MANIPULATOR;
    }
}