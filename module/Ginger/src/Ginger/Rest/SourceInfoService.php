<?php
namespace Ginger\Rest;

use Cl\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;
use Ginger\Model\Source\SourceLoaderInterface;
use Ginger\Job\JobLoaderInterface;
use Ginger\Service\DataStructure\TableStructureNormalizer;

/**
 * Description of SourceInfoService
 *
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 * @copyright (c) 2013, Alexander Miertsch
 */
class SourceInfoService extends AbstractRestfulController
{
    /**
     *
     * @var SourceLoaderInterface
     */
    protected $sourceLoader;

    /**
     *
     * @var JobLoaderInterface
     */
    protected $jobLoader;

    public function setSourceLoader(SourceLoaderInterface $sourceLoader)
    {
        $this->sourceLoader = $sourceLoader;
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

            $source = $config->getSource();

            if ($source->getId() != $id) {
                $source = $this->sourceLoader->getSource($id);
            }
        } else {
            $source = $this->sourceLoader->getSource($id);
        }

        if (!$source) {
            $this->getResponse()->setStatusCode(404);
            return new JsonModel();
        }

        return new JsonModel(array(
            'data_type' => $source->getDataType(),
            'data_structure' => $source->getDataStructure(),
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