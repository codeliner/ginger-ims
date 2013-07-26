<?php
namespace MockObject;

use Ginger\Model\Target\AbstractTarget;
/**
 * Description of DummyDocumentTarget
 *
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 * @copyright (c) 2013, Alexander Miertsch
 */
class DummyDocumentTarget extends AbstractTarget
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
        return static::DATA_TYPE_DOCUMENT_STRUCTURE;
    }

    public function getOptions()
    {
        return array();
    }

    public function setOptions(array $options)
    {

    }
}