<?php
namespace SqlConnect\Rest;

use Cl\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;
use Zend\Mvc\MvcEvent;
use SqlConnect\Model\Db\TableTarget;
use Ginger\Service\DataStructure\TableStructureNormalizer;
/**
 * Description of SourcesService
 *
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 * @copyright (c) 2013, Alexander Miertsch
 */
class TargetsService extends AbstractRestfulController
{
    const CACHE_KEY = "sqlconnect_targets";
    const TARGET_LINK = "sqlconnect/targets/:connection/show/:id";

    protected $registeredTargets;

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
        $targets = array();
        foreach($this->getTableNames() as $tableName) {
            $targets[] = $this->getElementData($tableName, false);
        }

        return new JsonModel($targets);
    }

    public function update($id, $data)
    {
        $elData = $this->getElementData($id, false);

        $targetLoader = $this->getServiceLocator()->get('target_loader');

        $config = $this->getConfig();

        if ($elData['is_target'] && !$data['is_target']) {
            $targetLoader->unregisterTarget($elData['connector_element_id']);

            unset($this->registeredTargets[$id]);
        } else if ($data['is_target']) {
            $elementId = $targetLoader->registerTarget(
                'SqlConnect',
                $this->connection . '::' . $id,
                'SqlConnect\Model\Db\TableTarget',
                $this->getLink($id)
                );

            $this->registeredTargets[$id] = array(
                'connector_element_id' => $elementId,
            );
        }

        $this->setConfig($this->registeredTargets);

        return new JsonModel(array('success' => true));
    }

    protected function getElementData($id, $withColumns = true)
    {
        if (is_null($this->registeredTargets)) {

            $this->registeredTargets = $this->getConfig();
        }

        $targetData = null;

        if (isset($this->registeredTargets[$id])) {
            $targetData = $this->registeredTargets[$id];
        }

        $targetElementId = 0;
        $isTarget = false;

        if ($targetData) {
            $targetElementId = $targetData['connector_element_id'];
            $isTarget = true;
        }

        if ($withColumns) {
            $tableTarget = new TableTarget(
                $targetElementId,
                $this->connection . '::' . $id,
                $this->getLink($id),
                'SqlConnect'
                );
            $tableTarget->setServiceLocator($this->getServiceLocator());
            $columns = $tableTarget->getDataStructure();
        } else {
            $columns = array();
        }

        $data = array(
            'id' => $id,
            'name' => $id,
            'connector_element_id' => $targetElementId,
            'link' => $this->getLink($id),
            'columns' => $columns,
            'is_target' => $isTarget
        );

        return $data;
    }

    protected function getTableNames()
    {
        $cache = $this->getServiceLocator()->get('data_cache');

        if ($cache->hasItem(static::CACHE_KEY . '_' . md5($this->connection))) {
            $tableNames = $cache->getItem(static::CACHE_KEY . '_' . md5($this->connection));
        } else {
            $targetAdapter = $this->getServiceLocator()->get('sqlconnect_adapter_factory')->factory($this->connection);

            $tableNames = $targetAdapter
                ->getConnection()
                ->getDriver()
                ->getSchemaManager($targetAdapter->getConnection())
                ->listTableNames();

            $cache->setItem(static::CACHE_KEY . '_' . md5($this->connection), $tableNames);
        }

        return $tableNames;
    }

    protected function getLink($id)
    {
        return str_replace(':connection', $this->connection, str_replace(':id', $id, static::TARGET_LINK));
    }

    protected function getConfig()
    {
        $config = $this->getServiceLocator()->get('module_loader')->loadModuleConfig('SqlConnect');

        if (!isset($config['targets'])) {
            return array();
        }

        if (!isset($config['targets'][$this->connection])) {
            return array();
        }

        return $config['targets'][$this->connection];
    }

    protected function setConfig($config)
    {
        $oldConfig = $this->getServiceLocator()->get('module_loader')->loadModuleConfig('SqlConnect');

        if (!isset($oldConfig['targets'])) {
            $oldConfig['targets'] = array();
        }
        $oldConfig['targets'][$this->connection] = $config;
        $this->getServiceLocator()->get('module_loader')->saveModuleConfig('SqlConnect', $oldConfig);
    }
}