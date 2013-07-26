<?php
namespace Ginger\Rest;

use Cl\Mvc\Controller\AbstractRestfulController;
use Zend\Validator\Regex;
use Zend\View\Model\JsonModel;
use Ginger\Job\JobLoaderInterface;
use Ginger\Job\Job;

/**
 * Description of JobsService
 *
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 * @copyright (c) 2013, Alexander Miertsch
 */
class JobsService extends AbstractRestfulController
{
    const ERROR_JOB_EXISTS = 'Job already exists';
    const ERROR_INVALID_JOB_NAME = 'The job name is invalid';
    /**
     *
     * @var JobLoaderInterface
     */
    protected $jobLoader;

    public function setJobLoader($jobLoader)
    {
        $this->jobLoader = $jobLoader;
    }

    public function create($data)
    {
        $response = array();

        if ($this->jobLoader->hasJob($data['name'])) {
            //send a 409 Conflict response
            $this->getResponse()->setStatusCode(409);
            $response['error'] = $this->getServiceLocator()->get('translator')->translate(static::ERROR_JOB_EXISTS);
            goto SEND_RESPONSE;
        } else {
            if (!$this->getJobNameValidator()->isValid($data['name'])) {
                $response['success'] = false;
                $response['error'] = $this->getServiceLocator()->get('translator')->translate(static::ERROR_INVALID_JOB_NAME);
                goto SEND_RESPONSE;
            }

            $job = new Job($data['name']);
            $job->setDescription($data['description']);
            $this->jobLoader->saveJob($job);

            $response = $data;
        }
        SEND_RESPONSE:
        return new JsonModel($response);
    }

    public function delete($id)
    {
        $result = $this->jobLoader->deleteJob($id);

        if (!$result) {
            return $this->getResponse()->setStatusCode(404)->setContent('Failed to delete the job.');
        }

        return new JsonModel(array('success' => true));
    }

    public function get($name)
    {
        $job = $this->jobLoader->loadJob($name);

        if (is_null($job)) {
            $this->getResponse()->setStatusCode(404);
            return new JsonModel();
        }

        $maxRuns = $this->getParam('max-jobruns', 10);

        return new JsonModel($job->getArrayCopy(true, $maxRuns));
    }

    public function getList()
    {
        $jobs = $this->jobLoader->getJobs();
        $jobsData = array();

        foreach ($jobs as $job) {
            $jobsData[] = $job->getArrayCopy(false);
        }

        return new JsonModel($jobsData);
    }

    public function update($id, $data)
    {
        if (!$this->jobLoader->hasJob($id)) {
            return $this->getResponse()->setStatusCode(404)->setContent('Job could not be found');
        }

        $job = $this->jobLoader->loadJob($id);
        $job->setDescription($data['description']);
        $job->setBreakOnFailure((bool)$data['break_on_failure']);

        $this->jobLoader->saveJob($job);

        return new JsonModel($job->getArrayCopy(true));
    }

    /**
     * @return Regex
     */
    protected function getJobNameValidator()
    {
        return new Regex('/^[a-zA-Z0-9 äÄüÜöÖß\-_]+$/');
    }
}