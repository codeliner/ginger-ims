<?php
namespace Ginger\Rest;

use Cl\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;
use Ginger\Job\JobLoaderInterface;
use Ginger\Job\Job;
use Ginger\Job\Task\JobTask;
use Ginger\Model\Source;
use Ginger\Model\Target;
use Ginger\Model\Mapper;
use Ginger\Model\Feature;
use Zend\Mvc\MvcEvent;
/**
 * Description of TasksService
 *
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 * @copyright (c) 2013, Alexander Miertsch
 */
class TasksService extends AbstractRestfulController
{
    /**
     *
     * @var JobLoaderInterface
     */
    protected $jobLoader;
    /**
     *
     * @var Source\SourceLoaderInterface
     */
    protected $sourceLoader;

    /**
     *
     * @var Target\TargetLoaderInterface
     */
    protected $targetLoader;

    /**
     *
     * @var Mapper\MapperLoaderInterface
     */
    protected $mapperLoader;


    /**
     *
     * @var Feature\FeatureLoaderInterface
     */
    protected $featureLoader;

    /**
     *
     * @var Job
     */
    protected $job;

    public function setJobLoader($jobLoader)
    {
        $this->jobLoader = $jobLoader;
    }

    public function setSourceLoader($sourceLoader)
    {
        $this->sourceLoader = $sourceLoader;
    }

    public function setTargetLoader($targetLoader)
    {
        $this->targetLoader = $targetLoader;
    }

    public function setMapperLoader($mapperLoader)
    {
        $this->mapperLoader = $mapperLoader;
    }

    public function setFeatureLoader($featureLoader)
    {
        $this->featureLoader = $featureLoader;
    }

    /**
     * Register the default events for this controller
     *
     * @return void
     */
    protected function attachDefaultListeners()
    {
        parent::attachDefaultListeners();
        $events = $this->getEventManager();
        $events->attach(MvcEvent::EVENT_DISPATCH, array($this, 'checkJobname'), 100);
    }

    public function checkJobname(MvcEvent $e)
    {
        $jobname = $e->getRouteMatch()->getParam('jobname', '');

        if (!$this->jobLoader->hasJob($jobname)) {
            $this->getResponse()->setStatusCode(404);
            return $this->getResponse();
        }

        $this->job = $this->jobLoader->loadJob($jobname);
    }

    public function create($data)
    {
        $task = new JobTask();

        $source = $this->sourceLoader->getSource($data['source']['id']);
        $source->setOptions($data['source']['options']);
        $task->setSource($source);

        $target = $this->targetLoader->getTarget($data['target']['id']);
        $target->setOptions($data['target']['options']);
        $task->setTarget($target);

        if (isset($data['mapper'])) {
            $mapper = $this->mapperLoader->getMapper($data['mapper']['id']);
            $mapper->setOptions($data['mapper']['options']);
            $task->setMapper($mapper);
        }

        if (isset($data['features'])) {
            $featureCollection = array();
            foreach ($data['features'] as $featureData) {
                $feature = $this->featureLoader->getFeature($featureData['id']);
                $feature->setOptions($featureData['options']);
                $featureCollection[] = $feature;
            }
            $task->setFeatures($featureCollection);
        }

        $this->job->addTask($task);

        $this->jobLoader->saveJob($this->job);

        return new JsonModel($task->getArrayCopy());
    }

    public function delete($id)
    {
        $tasks = $this->job->getTasks();
        $newTasks = array();
        $newTaskIds = array();
        foreach($tasks as $task) {
            if ($task->getId() != $id) {
                $newTasks[] = $task;
                $newTaskIds[] = $task->getId();
            }
        }

        $this->job->setTasks($newTasks);
        $this->jobLoader->saveJob($this->job);

        return new JsonModel(array('success' => true));
    }

    public function get($id)
    {
        $tasks = $this->job->getTasks();

        $taskData = array();

        foreach($tasks as $task) {
            if ($task->getId() == $id) {
                $taskData[] = $task->getArrayCopy();
                break;
            }
        }

        if (empty($taskData)) {
            return $this->getResponse()->setStatusCode(404)->setContent('Task can not be found');
        }

        return new JsonModel($taskData);
    }

    public function getList()
    {
        $tasks = $this->job->getTasks();

        $tasksData = array();

        foreach($tasks as $task) {
            $tasksData[] = $task->getArrayCopy();
        }

        return new JsonModel($tasksData);
    }

    public function update($id, $data)
    {
        $tasks = $this->job->getTasks();
        $taskFound = false;
        foreach($tasks as $task) {
            if ($task->getId() == $id) {
                $taskFound = true;
                $source = $this->sourceLoader->getSource($data['source']['id']);
                $source->setOptions($data['source']['options']);
                $task->setSource($source);

                $target = $this->targetLoader->getTarget($data['target']['id']);
                $target->setOptions($data['target']['options']);
                $task->setTarget($target);

                if (isset($data['mapper'])) {
                    $mapper = $this->mapperLoader->getMapper($data['mapper']['id']);
                    $mapper->setOptions($data['mapper']['options']);
                    $task->setMapper($mapper);
                }

                if (isset($data['features'])) {
                    $featureCollection = array();
                    foreach ($data['features'] as $featureData) {
                        $feature = $this->featureLoader->getFeature($featureData['id']);
                        $feature->setOptions($featureData['options']);
                        $featureCollection[] = $feature;
                    }
                    $task->setFeatures($featureCollection);
                }

                $this->jobLoader->saveJob($this->job);
                $taskFound = $task;
                break;
            }
        }

        if (!$taskFound) {
            return $this->getResponse()->setStatusCode(404);
        }

        return new JsonModel($taskFound->getArrayCopy());
    }
}

