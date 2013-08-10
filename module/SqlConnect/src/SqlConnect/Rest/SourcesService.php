<?php
namespace SqlConnect\Rest;

use Cl\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;
use Zend\Mvc\MvcEvent;
use SqlConnect\Model\Db\TableSource;
use Ginger\Service\DataStructure\TableStructureNormalizer;
/**
 * Description of SourcesService
 *
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 * @copyright (c) 2013, Alexander Miertsch
 */
class SourcesService extends AbstractRestfulController
{
    const CACHE_KEY = "sqlconnect_sources";
    const SOURCE_LINK = "sqlconnect/sources/:connection/show/:id";

    protected $registeredSources;

    protected $connection;

    /**
     * Register the default events for this controller
     *
     * @return void
     */
    protected function attachDefaultListeners()
    {
        parent::attachDefaultListeners();
        $events = $this->getEventManager();
        $events->attach(MvcEvent::EVENT_DISPATCH, array($this, 'checkConnection'), 100);
    }

    public function checkConnection(MvcEvent $e)
    {
        $this->connection = $e->getRouteMatch()->getParam('connection');

        $config = $this->getServiceLocator()->get('module_loader')->loadModuleConfig('SqlConnect');

        if (!array_key_exists($this->connection, $config['connections'])) {
            $this->getResponse()->setStatusCode(404)->setContent('Connection can not be found');
            return $this->getResponse();
        }
    }

    public function create($data)
    {
        throw new \BadMethodCallException('Create method is not supported by this service');
    }

    public function delete($id)
    {
        throw new \BadMethodCallException('Delete method is not supported by this service');
    }

    public function get($id)
    {
        return new JsonModel($this->getElementData($id));
    }

    public function getList()
    {
        $sources = array();
        foreach($this->getTableNames() as $tableName) {
            $sources[] = $this->getElementData($tableName, false);
        }

        return new JsonModel($sources);
    }

    public function update($id, $data)
    {
        $elData = $this->getElementData($id, false);

        $sourceLoader = $this->getServiceLocator()->get('source_loader');

        $config = $this->getConfig();

        if ($elData['is_source'] && !$data['is_source']) {
            $sourceLoader->unregisterSource($elData['connector_element_id']);

            unset($this->registeredSources[$id]);
        } else if ($data['is_source']) {
            $elementId = $sourceLoader->registerSource(
                'SqlConnect',
                $this->connection . '::' . $id,
                'SqlConnect\Model\Db\TableSource',
                $this->getLink($id)
                );

            $this->registeredSources[$id] = array(
                'connector_element_id' => $elementId,
            );
        }

        $this->setConfig($this->registeredSources);

        return new JsonModel(array('success' => true));
    }

    protected function getElementData($id, $withColumns = true)
    {
        if (is_null($this->registeredSources)) {
            $this->registeredSources = $this->getConfig();
        }

        $sourceData = null;

        if (isset($this->registeredSources[$id])) {
            $sourceData = $this->registeredSources[$id];
        }

        $sourceElementId = 0;
        $isSource = false;

        if ($sourceData) {
            $sourceElementId = $sourceData['connector_element_id'];
            $isSource = true;
        }

        if ($withColumns) {
            $tableSource = new TableSource(
                $sourceElementId,
                $this->connection . '::' . $id,
                $this->getLink($id),
                'SqlConnect'
                );
            $tableSource->setServiceLocator($this->getServiceLocator());
            $columns = $tableSource->getDataStructure();
        } else {
            $columns = array();
        }

        $data = array(
            'id' => $id,
            'name' => $id,
            'connector_element_id' => $sourceElementId,
            'link' => $this->getLink($id),
            'columns' => $columns,
            'is_source' => $isSource
        );

        return $data;
    }

    protected function getTableNames()
    {
        $cache = $this->getServiceLocator()->get('data_cache');

        if ($cache->hasItem(static::CACHE_KEY . '_' . md5($this->connection))) {
            $tableNames = $cache->getItem(static::CACHE_KEY . '_' . md5($this->connection));
        } else {
            $sourceAdapter = $this->getServiceLocator()->get('sqlconnect_adapter_factory')->factory($this->connection);

            $tableNames = $sourceAdapter
                ->getConnection()
                ->getDriver()
                ->getSchemaManager($sourceAdapter->getConnection())
                ->listTableNames();

            $cache->setItem(static::CACHE_KEY . '_' . md5($this->connection), $tableNames);
        }

        return $tableNames;
    }

    protected function getLink($id)
    {
        return str_replace(':connection', $this->connection, str_replace(':id', $id, static::SOURCE_LINK));
    }

    protected function getConfig()
    {
        $config = $this->getServiceLocator()->get('module_loader')->loadModuleConfig('SqlConnect');

        if (!isset($config['sources'])) {
            return array();
        }

        if (!isset($config['sources'][$this->connection])) {
            return array();
        }

        return $config['sources'][$this->connection];
    }

    protected function setConfig($config)
    {
        $oldConfig = $this->getServiceLocator()->get('module_loader')->loadModuleConfig('SqlConnect');

        if (!isset($oldConfig['sources'])) {
            $oldConfig['sources'] = array();
        }
        $oldConfig['sources'][$this->connection] = $config;
        $this->getServiceLocator()->get('module_loader')->saveModuleConfig('SqlConnect', $oldConfig);
    }
}