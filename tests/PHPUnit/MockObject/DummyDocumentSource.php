<?php
namespace MockObject;

use Ginger\Model\Source\AbstractSource;
/**
 * Description of DummyDocumentSource
 *
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 * @copyright (c) 2013, Alexander Miertsch
 */
class DummyDocumentSource extends AbstractSource
{
    public function getData()
    {
        return array();
    }

    public function getDataStructure()
    {
        return array();
    }

    public function getDataType()
    {
        return static::DATA_TYPE_DOCUMENT_STRUCTURE;
    }

    public function getItemCount()
    {
        return 0;
    }

    public function getOptions()
    {
        return array();
    }

    public function setOptions(array $options)
    {

    }
}