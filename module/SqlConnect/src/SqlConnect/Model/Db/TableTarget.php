<?php
namespace SqlConnect\Model\Db;

use Ginger\Model\Connector\ConnectorEvent;
use Ginger\Model\Target\AbstractTarget;
use Ginger\Service\DataStructure\TableStructureNormalizer;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\EventManager\EventManagerInterface;
use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\ORM\AbstractQuery;
use SqlConnect\Service\Db\AdapterFactory;
/**
 * Description of TableTarget
 *
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 * @copyright (c) 2013, Alexander Miertsch
 */
class TableTarget extends AbstractTarget implements ServiceLocatorAwareInterface
{
    /**
     *
     * @var ServiceLocatorInterface
     */
    protected $serviceLocator;

    /**
     * @var string
     */
    protected $table;

    /**
     *
     * @var string
     */
    protected $connection;

    /**
     *
     * @var QueryBuilder
     */
    protected $adapter;

    protected $emptyTable = false;

    protected function init()
    {
        $this->extractInfo($this->name);
    }

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
        $this->listeners[] = $events->attach(ConnectorEvent::EVENT_START_INSERT, array($this, 'onStartInsert'));
    }

    public function onStartInsert()
    {
        if ($this->emptyTable) {
            $truncateSql = $this->getAdapter()->getConnection()->getDatabasePlatform()->getTruncateTableSQL($this->table);
            $this->getAdapter()->getConnection()->query($truncateSql);
        }
    }

    public function addItem($item)
    {
        $this->getAdapter()->getConnection()->insert($this->table, $item);
    }

    public function getDataStructure()
    {
        $adapter = $this->getAdapter();
        $dataStructure = $adapter
            ->getConnection()
            ->getDriver()
            ->getSchemaManager($adapter->getConnection())
            ->listTableColumns($this->table);

        return TableStructureNormalizer::columnsToArray($dataStructure);
    }

    public function getDataType()
    {
        return static::DATA_TYPE_TABLE_STRUCTURE;
    }

    public function getOptions()
    {
        return array(
            'emptyTable' => $this->emptyTable,
        );
    }

    public function setOptions(array $options)
    {
        if (isset($options['emptyTable']))
            $this->emptyTable = (bool)$options['emptyTable'];
    }

    /**
     * @return QueryBuilder
     */
    protected function getAdapter()
    {
        if (is_null($this->adapter)) {

            /* @var $adapterFactory AdapterFactory */
            $adapterFactory = $this->serviceLocator->get('sqlconnect_adapter_factory');

            $this->adapter = $adapterFactory->factory($this->connection);
        }

        return $this->adapter;

    }

    protected function extractInfo($elementName)
    {
        $parts = explode('::', $elementName);
        $this->connection = $parts[0];
        $this->table = $parts[1];
    }
}