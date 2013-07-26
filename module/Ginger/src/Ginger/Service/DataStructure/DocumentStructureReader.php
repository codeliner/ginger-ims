<?php
namespace Ginger\Service\DataStructure;

use Ginger\Model\Connector\AbstractElement;
use Zend\Stdlib\ArrayUtils;
/**
 * Description of DocumentStructureReader
 *
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 * @copyright (c) 2013, Alexander Miertsch
 */
class DocumentStructureReader
{
    const LIST_TYPE = 'array';
    const DOC_TYPE = 'object';

    public static function readStructureFromArray(array $data)
    {
        $structure = array();

        $isList = ArrayUtils::isList($data, true);

        foreach ($data as $key => $value) {

            if ($isList) {
                $key = AbstractElement::DOCUMENT_COLLECTION_IDENTIFIER;
            }

            if (is_array($value)) {

                if (ArrayUtils::isHashTable($value)) {
                    $structure[$key] = array(
                        'name' => $key,
                        'type' => static::DOC_TYPE
                    );
                } else {
                    $structure[$key] = array(
                        'name' => $key,
                        'type' => static::LIST_TYPE
                    );
                }

                $deeperStructure = static::readStructureFromArray($value);

                foreach ($deeperStructure as $deeperEl) {
                    $deeperEl['name'] = $key . AbstractElement::DOCUMENT_ATTRIBUTE_SEPERATOR . $deeperEl['name'];

                    $structure[$deeperEl['name']] = $deeperEl;
                }
            } else {
                $structure[$key] = array(
                    'name' => $key,
                    'type' => gettype($value),
                );
            }
        }

        return array_values($structure);
    }
}