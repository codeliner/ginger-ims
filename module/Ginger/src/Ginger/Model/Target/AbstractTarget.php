<?php
namespace Ginger\Model\Target;

use Ginger\Model\Connector\AbstractElement;
/**
 * Description of AbstractTarget
 *
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 * @copyright (c) 2013, Alexander Miertsch
 */
abstract class AbstractTarget extends AbstractElement
{
    protected $action = "import";

    public function getAction()
    {
        return $this->action;
    }

    abstract public function addItem($item);

    abstract public function getDataStructure();

    abstract public function getDataType();
}