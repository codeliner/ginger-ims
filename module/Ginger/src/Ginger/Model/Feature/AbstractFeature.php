<?php
namespace Ginger\Model\Feature;

use Ginger\Model\Connector\AbstractElement;
use Ginger\Model\Connector\ConnectorEvent;
use Ginger\Model\Connector\Exception\InvalidItemTypeException;
use Ginger\Model\Connector\Exception\InvalidDataStructureException;
use Zend\EventManager\EventManagerInterface;
/**
 * Description of AbstractFeature
 *
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 * @copyright (c) 2013, Alexander Miertsch
 */
abstract class AbstractFeature extends AbstractElement
{
    const TYPE_FILTER      = "filter";
    const TYPE_VALIDATOR   = "validator";
    const TYPE_MANIPULATOR = "manipulator";
    const TYPE_MAPPER      = "mapper";
    const TYPE_OTHER       = "other";

    /**
     * Stores the attributes, that should be altered
     *
     * @var array
     */
    protected $attributesToAlter = array();

    /**
     * Defines the mapping site (source or target)
     *
     * If source is defined as mapping site, features are attached with priority 50 on the mapping event
     * If target is defined as mapping site, features are attached with priority -50 on the mapping event
     *
     * @var string
     */
    protected $siteToAlter = "source";

    /**
     * Override this flag in an extending class to define that the {@method alterValue} can work with arrays
     *
     * If it is set to false(default), the {@method onMapItem} iterates over each array
     * and pass only the elements of an array to {@method alterValue}
     *
     * @var boolean
     */
    protected $selfProcessingArrays = false;

    /**
     * Get the type of the feature
     *
     * The feature types are used to group the features
     *
     * @return string
     */
    abstract function getType();

    /**
     * Perform the filtering, mapping, etc. on the attribute value
     *
     * @return mixed The modified value
     */
    abstract function alterValue($value, $attributeToAlter, ConnectorEvent $e);

    public function attach(EventManagerInterface $events)
    {
        $priority = ($this->siteToAlter == "source")? 50 : -50;
        $this->listeners[] = $events->attach(ConnectorEvent::EVENT_MAP_ITEM, array($this, 'onMapItem'), $priority);
    }

    public function onMapItem(ConnectorEvent $e)
    {
        $item = $e->getItem();

        if (!is_array($item) && !$item instanceof \ArrayAccess) {
            throw new InvalidItemTypeException('Features can only work with an array or \ArrayAccess item');
        }

        $site = $e->{'get' . ucfirst($this->siteToAlter)}();

        $dataType = $site->getDataType();

        if ($dataType != static::DATA_TYPE_TABLE_STRUCTURE && $dataType != static::DATA_TYPE_DOCUMENT_STRUCTURE) {
            throw new InvalidDataStructureException(
                sprintf(
                    'Feature "%s" can not work with the data-structure provided by the %s "%s".',
                    get_class($this),
                    $this->siteToAlter,
                    $site->getName()
                ));
        }

        foreach ($this->attributesToAlter as $attributeToAlter) {
            $value = $this->getAttributeValue($dataType, $attributeToAlter, $item);
            if (is_array($value) && !$this->selfProcessingArrays) {
                foreach ($value as $i => $valueEntry) {
                    $value[$i] = $this->alterValue($valueEntry, $attributeToAlter, $e);
                }
            } else {
                $value = $this->alterValue($value, $attributeToAlter, $e);
            }

            $item = $this->setAttributeValue($dataType, $attributeToAlter, $value, $item);
        }

        $e->setItem($item);

        return $this->getMessage();
    }

    public function getOptions()
    {
        $options = array(
            'attributes_to_alter' => $this->attributesToAlter,
            'site_to_alter' => $this->siteToAlter,
        );

        return array_merge($options, $this->getAdvancedOptions());
    }

    public function setOptions(array $options)
    {
        if (isset($options['site_to_alter'])) {
            $this->siteToAlter = $options['site_to_alter'];
        }
        
        $this->attributesToAlter = $options['attributes_to_alter'];
        $this->setAdvancedOptions($options);
    }

    /**
     * Hookpoint: Return options for the feature, they are merged with the abstract options
     *
     * @return array
     */
    protected function getAdvancedOptions()
    {
        return array();
    }

    /**
     * Hookpoint: Set options for the feature
     *
     * Options contains abstract and advanced options
     *
     * @param array $options
     *
     * @return void
     */
    protected function setAdvancedOptions(array $options)
    {
        return;
    }

    /**
     * Hookpoint: Message is returned by onMapItm()
     *
     * Returns null by default, meaning no message will be logged
     *
     * @return null
     */
    public function getMessage()
    {
        return null;
    }
}