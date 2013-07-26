<?php
namespace Ginger\Rest;

use Cl\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;
use Ginger\Model\Target\TargetLoaderInterface;
use Ginger\Job\JobLoaderInterface;
use Ginger\Service\DataStructure\TableStructureNormalizer;

/**
 * Description of TargetInfoService
 *
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 * @copyright (c) 2013, Alexander Miertsch
 */
class TargetInfoService extends AbstractRestfulController
{
    /**
     *
     * @var TargetLoaderInterface
     */
    protected $targetLoader;

    /**
     *
     * @var JobLoaderInterface
     */
    protected $jobLoader;

    public function setTargetLoader(TargetLoaderInterface $targetLoader)
    {
        $this->targetLoader = $targetLoader;
    }

    public function setJobLoader(JobLoaderInterface $jobLoader)
    {
        $this->jobLoader = $jobLoader;
    }

    public function create($data)
    {
        $this->getResponse()->setStatusCode(405);
        return new JsonModel();
    }

    public function delete($id)
    {
        $this->getResponse()->setStatusCode(405);
        return new JsonModel();
    }

    public function get($id)
    {
        $jobname = $this->getEvent()->getRouteMatch()->getParam('jobname');

        if ($jobname != "-") {
            $job = $this->jobLoader->loadJob($jobname);
            $configId = $this->getEvent()->getRouteMatch()->getParam('configid');
            $configs = $job->getConfigurations();

            foreach ($configs as $config) {
                if ($config->getId() == $configId) {
                    break;
                }
            }

            $target = $config->getTarget();

            if ($target->getId() != $id) {
                $target = $this->targetLoader->getTarget($id);
            }
        } else {
            $target = $this->targetLoader->getTarget($id);
        }

        if (!$target) {
            $this->getResponse()->setStatusCode(404);
            return new JsonModel();
        }

        return new JsonModel(array(
            'data_type' => $target->getDataType(),
            'data_structure' => $target->getDataStructure(),
        ));
    }

    public function getList()
    {
        $this->getResponse()->setStatusCode(405);
        return new JsonModel();
    }

    public function update($id, $data)
    {
        $this->getResponse()->setStatusCode(405);
        return new JsonModel();
    }
}