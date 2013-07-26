<?php
namespace Ginger\Model\Feature;

use Zend\Filter\FilterPluginManager;
use Zend\Filter\FilterInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Ginger\Model\Connector\ConnectorEvent;
/**
 * Description of FilterFeature
 *
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 * @copyright (c) 2013, Alexander Miertsch
 */
class FilterFeature extends AbstractFeature implements ServiceLocatorAwareInterface
{
    const NAME_PREFIX = "Filter::";
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

    public function alterValue($value, $attributeToAlter, ConnectorEvent $e)
    {
        return $this->getFilter()->filter($value);
    }

    public function getType()
    {
        return static::TYPE_FILTER;
    }

    public function getName()
    {
        return static::NAME_PREFIX . $this->name;
    }

    /**
     *
     * @return FilterInterface
     */
    protected function getFilter()
    {
        return $this->serviceLocator->get('FilterPluginManager')->get($this->name);
    }
}