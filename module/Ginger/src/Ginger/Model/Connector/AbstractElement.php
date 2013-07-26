<?php
namespace Ginger\Model\Connector;

use Zend\EventManager\ListenerAggregateInterface;
use Zend\EventManager\EventManagerInterface;
/**
 * Description of AbstractElement
 *
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 * @copyright (c) 2013, Alexander Miertsch
 */
abstract class AbstractElement implements ListenerAggregateInterface
{
    const DATA_TYPE_TABLE_STRUCTURE      = 1;
    const DATA_TYPE_DOCUMENT_STRUCTURE   = 3;
    const DATA_TYPE_NOT_DEFINED          = 0;
    const DOCUMENT_ATTRIBUTE_SEPERATOR   = "::";
    const DOCUMENT_COLLECTION_IDENTIFIER = "[]";

    protected $id;

    protected $name;

    protected $link;

    protected $module;

    protected $listeners = array();

    abstract public function setOptions(array $options);

    abstract public function getOptions();

    public function __construct($id, $name, $link, $module)
    {
        $this->id = $id;
        $this->name = $name;
        $this->link = $link;
        $this->module = $module;
        $this->init();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getLink()
    {
        return $this->link;
    }

    public function getModule()
    {
        return $this->module;
    }

    public function attach(EventManagerInterface $events)
    {
    }

    public function detach(EventManagerInterface $events)
    {
        foreach ($this->listeners as $index => $listener) {
            if ($events->detach($listener)) {
                unset($this->listeners[$index]);
            }
        }
    }

    protected function init()
    {
    }

    /**
     * **************************************************************
     * Helper Methods, to get or set attributes from or to an array
     * in context of the defined data_types used in this class.
     * The methods are only visible for extending classes, cause
     * the usage in other contexts is not supported.
     * **************************************************************
     */

    protected function getAttributeValue($dataType, $attribute, &$data)
    {
        if ($dataType == static::DATA_TYPE_TABLE_STRUCTURE) {
            return $data[$attribute];
        } else if ($dataType == static::DATA_TYPE_DOCUMENT_STRUCTURE) {
            $keys = explode(static::DOCUMENT_ATTRIBUTE_SEPERATOR, $attribute);

            $levels = count($keys) - 1;

            return $this->getAttributeValueRecursive($data, $keys, 0, $levels);
        } else {
            return $data;
        }
    }

    private function getAttributeValueRecursive(&$data, $keys, $currentLevel, $levels)
    {
        //if the actual level is a list, we have to collect the values for all list elements
        if ($keys[$currentLevel] == static::DOCUMENT_COLLECTION_IDENTIFIER) {
            //if the list is the last key, we are fine and can return the complete data array
            if ($currentLevel == $levels) {
                return $data;
            }

            //oh ok, we have to go deeper, so we need a new array to collect the requested values
            $newArr = array();

            foreach ($data as $i => $value) {
                //collect the value for each element in the list
                $newArr[$i] = $this->getAttributeValueRecursive(
                    $value,
                    $keys,
                    $currentLevel + 1,
                    $levels);
            }

            return $newArr;
        }

        //no list detected, so we have an easy recursive level here
        if ($currentLevel == $levels) {
             return $data[$keys[$currentLevel]];
        } else {
            return $this->getAttributeValueRecursive($data[$keys[$currentLevel]], $keys, $currentLevel + 1, $levels);
        }
    }

    protected function setAttributeValue($dataType, $attribute, $value, &$data)
    {
        if ($dataType == static::DATA_TYPE_TABLE_STRUCTURE) {
            $data[$attribute] = $value;
            return $data;
        } else if ($dataType == static::DATA_TYPE_DOCUMENT_STRUCTURE) {
            $keys = explode(static::DOCUMENT_ATTRIBUTE_SEPERATOR, $attribute);

            $levels = count($keys) - 1;

            $this->setAttributeValueRecursive($data, $keys, 0, $levels, $value);

            return $data;
        } else {
            $data = $value;
            return $data;
        }
    }

    private function &setAttributeValueRecursive(&$data, $keys, $currentLevel, $levels, $value)
    {
        if ($keys[$currentLevel] == static::DOCUMENT_COLLECTION_IDENTIFIER) {
            if ($currentLevel == $levels) {
                $data = $value;
            }

            foreach($value as $i => $singleVal) {
                if (!isset($data[$i])) {
                    $data[$i] = array();
                }
                $data[$i] = $this->setAttributeValueRecursive($data[$i], $keys, $currentLevel + 1, $levels, $singleVal);
            }

            return $data;
        }

        if ($currentLevel == $levels) {
             $data[$keys[$currentLevel]] = $value;
        } else {
            $data[$keys[$currentLevel]] = $this->setAttributeValueRecursive($data[$keys[$currentLevel]], $keys, $currentLevel + 1, $levels, $value);
        }

        return $data;
    }
}