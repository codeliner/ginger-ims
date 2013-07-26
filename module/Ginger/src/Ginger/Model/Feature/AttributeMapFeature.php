<?php
namespace Ginger\Model\Feature;

use Ginger\Job\Run\Message;
use Ginger\Model\Connector\ConnectorEvent;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\EventManager\EventManagerInterface;
/**
 * Description of AttributeMapFeature
 *
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 * @copyright (c) 2013, Alexander Miertsch
 */
class AttributeMapFeature extends AbstractFeature implements ServiceLocatorAwareInterface
{
    protected $attributeMap = array();

    protected $message;

    protected $orgItem;

    protected $item;
    /**
     *
     * @var ServiceLocatorInterface
     */
    protected $serviceLocator;

    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }

    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
    }

    public function attach(EventManagerInterface $events)
    {
        $this->listeners[] = $events->attach(ConnectorEvent::EVENT_MAP_ITEM, array($this, 'onMapItemPre'), 1000);
        $this->listeners[] = $events->attach(ConnectorEvent::EVENT_MAP_ITEM, array($this, 'onMapItemPost'), -50);
    }

    public function getType()
    {
        return static::TYPE_MAPPER;
    }

    public function onMapItemPre(ConnectorEvent $e)
    {
        $item = $e->getItem();
        $this->orgItem = (is_object($item))? clone $item : $item;
    }

    public function onMapItemPost(ConnectorEvent $e)
    {
        $item = $e->getItem();

        if (!is_array($item) && !$item instanceof \ArrayAccess) {
            throw new InvalidItemTypeException('Features can only work with an array or \ArrayAccess item');
        }

        $target = $e->getTarget();

        $dataType = $target->getDataType();

        if ($dataType != static::DATA_TYPE_TABLE_STRUCTURE && $dataType != static::DATA_TYPE_DOCUMENT_STRUCTURE) {
            throw new InvalidDataStructureException(
                sprintf(
                    'Feature "%s" can not work with the data-structure provided by the %s "%s".',
                    get_class($this),
                    'target',
                    $target->getName()
                ));
        }

        foreach ($this->attributesToAlter as $attributeToAlter) {

            if (!isset($this->attributeMap[$attributeToAlter])) {
                $message = new Message(Message::TYPE_ERROR);
                $message->setText(
                    sprintf($this->getServiceLocator()->get('translator')->translate('ERROR::ATTRIBUTE_MAP_NOT_GIVEN'), $attributeToAlter)
                    );

                return $message;
            }

            $newKey = $this->attributeMap[$attributeToAlter];

            $value = $this->getAttributeValue($dataType, $attributeToAlter, $this->orgItem);

            $item = $this->setAttributeValue($dataType, $newKey, $value, $item);
        }

        $e->setItem($item);

        return $this->getMessage();
    }

    public function getAdvancedOptions()
    {
        return array(
            'attribute_map' => $this->attributeMap,
        );
    }

    public function setAdvancedOptions(array $options)
    {
        $this->attributeMap = $options['attribute_map'];
        $this->attributesToAlter = array_keys($options['attribute_map']);
    }

    public function getMessage()
    {
        $message = $this->message;
        $this->message = null;
        return $message;
    }

    public function alterValue($value, $attributeToAlter, ConnectorEvent $e)
    {
        //empty func, cause everything is done in self defined listeners
        return null;
    }
}