<?php
namespace Ginger\Model\Mapper;

use Ginger\Model\Connector\ConnectorEvent;
/**
 * Description of DocumentStructureMapper
 *
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 * @copyright (c) 2013, Alexander Miertsch
 */
class DocumentStructureMapper extends AbstractMapper
{
    public function mapItem($item, ConnectorEvent $e)
    {
        if (!is_array($item) && !$item instanceof \ArrayAccess) {
            throw new InvalidItemTypeException('DocumentStructure mapper can only work with an array or \ArrayAccess object');
        }

        $sourceDataType = $e->getSource()->getDataType();
        $targetDataType = $e->getTarget()->getDataType();

        $mappedItem = array();

        foreach ($this->mapping as $sourceKey => $targetKey) {
            $value = $this->getAttributeValue($sourceDataType, $sourceKey, $item);
            $this->setAttributeValue($targetDataType, $targetKey, $value, $mappedItem);
        }

        return $mappedItem;
    }
}