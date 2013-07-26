<?php
namespace Ginger\Rest;

use Cl\Mvc\Controller\AbstractRestfulController;
use Ginger\Job\Run\LoggerInterface;
use Zend\View\Model\JsonModel;

/**
 * Description of LatestJobRunsService
 *
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 * @copyright (c) 2013, Alexander Miertsch
 */
class LatestJobRunsService extends AbstractRestfulController
{
    /**
     *
     * @var LoggerInterface
     */
    protected $logger;

    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
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
        $this->getResponse()->setStatusCode(405);
        return new JsonModel();
    }

    public function getList()
    {
        $jobRuns = $this->logger->getLatestJobRuns(5);

        $jobRunsList = array();

        foreach ($jobRuns as $jobRun) {
            $jobRunsList[] = $jobRun->getArrayCopy();
        }

        return new JsonModel($jobRunsList);
    }

    public function update($id, $data)
    {
        $this->getResponse()->setStatusCode(405);
        return new JsonModel();
    }
}