<?php
namespace Ginger\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Ginger\Job\JobLoaderInterface;
use Cl\Stdlib\Formatter;
/**
 * Description of JobdataController
 *
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 * @copyright (c) 2013, Alexander Miertsch
 */
class JobdataController extends AbstractActionController
{
    /**
     *
     * @var JobLoaderInterface
     */
    protected $jobLoader;

    public function setJobLoader($jobLoader)
    {
        $this->jobLoader = $jobLoader;
    }

    public function exportjobAction()
    {
        //@todo gleich als preset exportieren und dann kann der job über den installer wieder importiert werden
    }

    public function exportconfigAction()
    {
        $jobname = $this->getEvent()->getRouteMatch()->getParam('jobname');
        $configId = $this->getEvent()->getRouteMatch()->getParam('id');

        $job = $this->jobLoader->loadJob($jobname);

        if ($job) {
            $configs = $job->getConfigurations();

            foreach ($configs as $config) {
                if ($config->getId() == $configId) {
                    $configData = $config->getArrayCopy();
                    unset($configData['id']);

                    return $this->sendDownload(
                        \Zend\Json\Encoder::encode($configData),
                        'application/json',
                        Formatter::urlClean($job->getName()) . '_' . 'configuration_' . $configId . '.json'
                        );
                }
            }
        }



        return $this->pageNotFound()->setTerminal(true);
    }

    public function importconfigAction()
    {
        $configData = file_get_contents($_FILES['import-file']['tmp_name']);

        $view = new ViewModel(array('config' => $configData));
        $view->setTerminal(true);

        return $view;
    }
}