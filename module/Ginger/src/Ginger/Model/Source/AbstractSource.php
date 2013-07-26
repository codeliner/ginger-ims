<?php
namespace Ginger\Model\Source;

use Ginger\Model\Connector\AbstractElement;
/**
 * Description of AbstractSource
 *
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 * @copyright (c) 2013, Alexander Miertsch
 */
abstract class AbstractSource extends AbstractElement
{
    protected $itemName = "dataset";

    public function getItemName()
    {
        return $this->itemName;
    }

    abstract public function getItemCount();

    abstract public function getDataType();

    abstract public function getDataStructure();

    /**
     * @return \Traversable
     */
    abstract public function getData();
}