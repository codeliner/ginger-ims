<?php
namespace Ginger\Rest;

use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;
use Ginger\Service\Module\ModuleLoaderInterface;
/**
 * Description of JobsService
 *
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 * @copyright (c) 2013, Alexander Miertsch
 */
class ModulesService extends AbstractRestfulController
{
    /**
     *
     * @var ModuleLoaderInterface
     */
    protected $moduleLoader;


    public function create($data)
    {

    }

    public function delete($id)
    {

    }

    public function setModuleLoader($moduleLoader)
    {
        $this->moduleLoader = $moduleLoader;
    }

    
    public function get($id)
    {
        $config = $this->getServiceLocator()->get('configuration');

        $modules = $config['connect_modules'];

        if (!isset($modules[$id])) {
            $this->getResponse()->setStatusCode(404)->setContent('Module not found');
            return $this->getResponse();
        }

        $moduleData = $modules[$id];
        $moduleData['module'] = $id;

        $moduleData['configuration'] = $this->moduleLoader->loadModuleConfig($id);

        return new JsonModel($moduleData);
    }

    public function getList()
    {
        $config = $this->getServiceLocator()->get('configuration');

        $modules = $config['connect_modules'];
        $moduleList = array();

        foreach($modules as $moduleName => $moduleData) {
            $moduleData['module'] = $moduleName;
            $moduleList[] = $moduleData;
        }

        return new JsonModel($moduleList);
    }

    public function update($id, $data)
    {

    }
}