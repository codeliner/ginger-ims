<?php
namespace MockObject;

use Ginger\Model\Target\AbstractTarget;
/**
 * Description of DummyTableTarget
 *
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 * @copyright (c) 2013, Alexander Miertsch
 */
class DummyTableTarget extends AbstractTarget
{
    public function addItem($item)
    {

    }

    public function getDataStructure()
    {
        return array();
    }

    public function getDataType()
    {
        return static::DATA_TYPE_TABLE_STRUCTURE;
    }

    public function getOptions()
    {
        return array();
    }

    public function setOptions(array $options)
    {

    }

    public function getAttrValue($dataType, $attribute, $data)
    {
        return $this->getAttributeValue($dataType, $attribute, $data);
    }

    public function setAttrValue($dataType, $attribute, $value, &$data)
    {
        return $this->setAttributeValue($dataType, $attribute, $value, $data);
    }
}