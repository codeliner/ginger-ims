<?php
namespace Ginger\Rest;

use Cl\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;
use Zend\Mvc\MvcEvent;
use Ginger\Job\JobLoaderInterface;
use Ginger\Job\Job;
/**
 * Description of JobRunsService
 *
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 * @copyright (c) 2013, Alexander Miertsch
 */
class JobRunsService extends AbstractRestfulController
{
    /**
     *
     * @var Job
     */
    protected $job;

    /**
     *
     * @var JobLoaderInterface
     */
    protected $jobLoader;

    public function setJobLoader($jobLoader)
    {
        $this->jobLoader = $jobLoader;
    }

    /**
     * Register the default events for this controller and a custom listener
     *
     * @return void
     */
    protected function attachDefaultListeners()
    {
        parent::attachDefaultListeners();
        $events = $this->getEventManager();
        $events->attach(MvcEvent::EVENT_DISPATCH, array($this, 'checkJobname'), 100);
    }

    /**
     * PreDispatch Listener to check if provided jobname is valid
     * 
     * OnSucceess: Service is populated with the job
     * OnError: Dispatch is aborted and a 404 Error is sent back to client
     * 
     * @param \Zend\Mvc\MvcEvent $e
     * @return void
     */
    public function checkJobname(MvcEvent $e)
    {
        $jobname = $e->getRouteMatch()->getParam('jobname', '');

        if (!$this->jobLoader->hasJob($jobname)) {
            $this->getResponse()->setStatusCode(404)->setContent('Job not found');
            return $this->getResponse();
        }

        $this->job = $this->jobLoader->loadJob($jobname);
    }

    /**
     * Create a new jobrun
     * 
     * Service needs no data for a new jobrun, so $data array is ignored
     * 
     * @param array $data
     * @return \Zend\View\Model\JsonModel
     */
    public function create($data)
    {
        $jobRunId = $this->job->createRun();

        return new JsonModel(array('id' => $jobRunId));
    }

    /**
     * Delete a jobrun
     * 
     * @param int $id
     * @return \Zend\View\Model\JsonModel
     */
    public function delete($id)
    {
        $success = $this->job->getLogger()->deleteJobRun($id);

        if (!$success) {
            return $this->getResponse()->setStatusCode(404)->setContent('Jobrun could not be found');
        }

        return new JsonModel(array('success' => $success));
    }

    /**
     * Return a JSON represantation of requested jobrun
     * 
     * @param int $id
     * @return \Zend\View\Model\JsonModel
     */
    public function get($id)
    {
        $jobRun = $this->job->getLogger()->getJobRun($id);

        return new JsonModel($jobRun->getArrayCopy());
    }

    /**
     * Get all jobruns as a JSON list
     * 
     * @return \Zend\View\Model\JsonModel
     */
    public function getList()
    {
        $jobRuns = $this->job->getLogger()->getJobRuns($this->job->getName());

        $jobRunsList = array();

        foreach ($jobRuns as $jobRun) {
            $jobRunsList[] = $jobRun->getArrayCopy();
        }

        return new JsonModel($jobRunsList);
    }

    /**
     * Trigger a previous created jobrun
     * 
     * The http put method (= update resource in REST context) is used in another
     * context here, so the $data array is ignored
     * 
     * @param int   $id
     * @param array $data
     * @return \Zend\View\Model\JsonModel
     */
    public function update($id, $data)
    {
        $this->job->run($id);

        $jobRun = $this->job->getLogger()->getJobRun($id);

        return new JsonModel($jobRun->getArrayCopy());
    }
}