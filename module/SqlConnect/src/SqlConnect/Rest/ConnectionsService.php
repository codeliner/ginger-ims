<?php
namespace SqlConnect\Rest;

use Cl\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;
use SqlConnect\Service\Db\AdapterFactory;

/**
 * Description of ConnectionsService
 *
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 * @copyright (c) 2013, Alexander Miertsch
 */
class ConnectionsService extends AbstractRestfulController
{
    /**
     *
     * @var AdapterFactory
     */
    protected $adapterFactory;

    public function setAdapterFactory($adapterFactory)
    {
        $this->adapterFactory = $adapterFactory;
    }

    public function create($data)
    {
        $config = $this->adapterFactory->getModuleLoader()->loadModuleConfig('SqlConnect');
        $connections = $config['connections'];
        $connections[$data['name']] = array(
            'params' => $data['params'],
            'isSource' => $data['isSource'],
            'isTarget' => $data['isTarget'],
            );
        $config['connections'] = $connections;

        $this->adapterFactory->getModuleLoader()->saveModuleConfig('SqlConnect', $config);

        return new JsonModel($data);
    }

    public function delete($id)
    {
        $config = $this->adapterFactory->getModuleLoader()->loadModuleConfig('SqlConnect');
        $connections = $config['connections'];
        unset($connections[$id]);

        $config['connections'] = $connections;

        $this->adapterFactory->getModuleLoader()->saveModuleConfig('SqlConnect', $config);

        return new JsonModel(array('success' => true));
    }

    public function get($id)
    {
        $config = $this->adapterFactory->getModuleLoader()->loadModuleConfig('SqlConnect');
        $connections = $config['connections'];
        $connectionData = null;
        foreach ($connections as $name => $config) {
            if ($name == $id) {
                $connectionData = array_merge($config, array('name' => $config));
            }
        }

        if (is_null($connectionData)) {
            return $this->getResponse()->setStatusCode(404)->setResponse('Connection could not be found');
        }

        return new JsonModel($connectionData);
    }

    public function getList()
    {
        $config = $this->adapterFactory->getModuleLoader()->loadModuleConfig('SqlConnect');
        $connections = $config['connections'];
        $connectionsData = array();
        foreach ($connections as $name => $config) {
            $connectionsData[] = array_merge($config, array('name' => $name));
        }

        return new JsonModel($connectionsData);
    }

    public function update($id, $data)
    {
        $config = $this->adapterFactory->getModuleLoader()->loadModuleConfig('SqlConnect');
        $connections = $config['connections'];
        $connections[$id] = array(
            'params' => $data['params'],
            'isSource' => $data['isSource'],
            'isTarget' => $data['isTarget'],
            );
        $config['connections'] = $connections;

        $this->adapterFactory->getModuleLoader()->saveModuleConfig('SqlConnect', $config);

        $data['name'] = $id;

        return new JsonModel($data);
    }

    public function testAction()
    {
        $postArr = $this->getRequest()->getPost()->toArray();
        $connection = json_decode($postArr['connection'], true);

        try {
            $adapter = $this->adapterFactory->factory('test', $connection);

            $adapter->getConnection()
                ->getDriver()
                ->getSchemaManager($adapter->getConnection())
                ->listTableNames();

        } catch (\Exception $e) {
            return $this->getResponse()->setStatusCode(404)->setContent($e->getMessage());
        }

        return new JsonModel(array('success' => true));
    }
}