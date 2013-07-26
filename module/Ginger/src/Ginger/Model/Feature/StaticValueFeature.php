<?php
namespace Ginger\Model\Feature;

use Ginger\Model\Connector\ConnectorEvent;
/**
 * Description of StaticValueFeature
 *
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 * @copyright (c) 2013, Alexander Miertsch
 */
class StaticValueFeature extends AbstractFeature
{
    protected $staticValue = null;

    /**
     * Always return the staticValue, that is defined via config
     *
     * @param type $value
     * @param type $attributeToAlter
     * @param \Ginger\Model\Connector\ConnectorEvent $e
     *
     * @return mixed
     */
    public function alterValue($value, $attributeToAlter, ConnectorEvent $e)
    {
        if ($this->staticValue == "TRUE") {
            return true;
        } elseif ($this->staticValue == "FALSE") {
            return false;
        } else {
            return $this->staticValue;
        }
    }

    public function getType()
    {
        return static::TYPE_MANIPULATOR;
    }

    protected function getAdvancedOptions()
    {
        return array(
            'static_value' => $this->staticValue,
        );
    }

    protected function setAdvancedOptions(array $options)
    {
        $this->staticValue = $options['static_value'];
    }

    /**
     * Placebo override, cause we want to set a static value and don't need to know the org value, if any exists
     *
     * @param type $dataType
     * @param type $attribute
     * @param type $data
     *
     * @return null
     */
    protected function getAttributeValue($dataType, $attribute, &$data)
    {
        return null;
    }
}