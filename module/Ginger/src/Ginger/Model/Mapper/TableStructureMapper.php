<?php
namespace Ginger\Model\Mapper;

use Ginger\Model\Connector\Exception\InvalidItemTypeException;
use Ginger\Model\Connector\ConnectorEvent;
/**
 * Description of TableStructureMapper
 *
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 * @copyright (c) 2013, Alexander Miertsch
 */
class TableStructureMapper extends AbstractMapper
{
    public function mapItem($item, ConnectorEvent $e)
    {
        if (!is_array($item) && !$item instanceof \ArrayAccess) {
            throw new InvalidItemTypeException('TableStructure mapper can only work with an array or \ArrayAccess ');
        }

        $mapedItem = array();

        foreach ($item as $key => $value) {
            if (isset($this->mapping[$key])) {
                $mapedItem[$this->mapping[$key]] = $value;
            }


        }

        return $mapedItem;
    }
}