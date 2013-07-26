<?php

/**
 * Index Controller
 */

namespace Ginger\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Codelinerjs\Javascript\Loader\JsLoaderAwareInterface;
use Codelinerjs\Javascript\Loader\AbstractLoader;

class IndexController extends AbstractActionController implements JsLoaderAwareInterface
{
    /**
     *
     * @var AbstractLoader
     */
    protected $jsLoader;

    public function setJsLoader(AbstractLoader $jsLoader)
    {
        $this->jsLoader = $jsLoader;
    }

    public function indexAction()
    {
        $config = $this->getServiceLocator()->get('configuration');

        $modules = $config['connect_modules'];
        $moduleList = array();

        foreach($modules as $moduleName => $moduleData) {
            $moduleData['module'] = $moduleName;
            $moduleList[] = $moduleData;
        }

        $this->jsLoader->addUserVars(array(
            'connect_modules' => $moduleList,
        ));

        return new ViewModel();
    }

    public function ieAdviseAction()
    {
        $viewModel = new ViewModel(array('host' => $_SERVER['HTTP_HOST']));

        $viewModel->setTerminal(true);

        return $viewModel;
    }
}
