<?php
namespace Ginger\Service\DataStructure;
/**
 * Description of TableStructureNormalizer
 *
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 * @copyright (c) 2013, Alexander Miertsch
 */
class TableStructureNormalizer
{
    public static function columnsToArray(array $columns)
    {
        $columnList = array();

        foreach ($columns as $column) {
            $columnData = $column->toArray();
            $columnData['type'] = $columnData['type']->getName();
            $columnList[] = $columnData;
        }

        return $columnList;
    }
}