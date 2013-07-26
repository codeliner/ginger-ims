<?php
namespace MockObject;

use Ginger\Model\Connector\AbstractElement;
use Ginger\Model\Source\AbstractSource;
/**
 * Description of TableSource
 *
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 * @copyright (c) 2013, Alexander Miertsch
 */
class TableSource extends AbstractSource
{
    protected $data = array();

    protected $dataStructure = array();

    protected $options = array();

    public function setData($data)
    {
        $this->data = $data;
    }

    public function getData()
    {
        return $this->data;
    }

    public function getDataStructure()
    {
        return $this->dataStructure;
    }

    public function setDataStructure($dataStructure)
    {
        $this->dataStructure = $dataStructure;
    }

    public function getDataType()
    {
        return AbstractElement::DATA_TYPE_TABLE_STRUCTURE;
    }

    public function getItemCount()
    {
        return count($this->data);
    }

    public function getOptions()
    {
        return $this->options;
    }

    public function setOptions(array $options)
    {
        $this->options = $options;
    }
}