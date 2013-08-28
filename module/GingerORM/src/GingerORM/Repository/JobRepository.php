<?php
namespace GingerORM\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\AbstractQuery;
use Ginger\Job\JobLoaderInterface;
use Ginger\Job\Job;
use Ginger\Job\Task\JobTask;
use Ginger\Model\Connector\Connector;
use Ginger\Model\Source\SourceLoaderInterface;
use Ginger\Model\Target\TargetLoaderInterface;
use Ginger\Model\Mapper\MapperLoaderInterface;
use Ginger\Model\Feature\FeatureLoaderInterface;
use GingerORM\Entity\Job as JobEntity;
use GingerORM\Entity\Task as TaskEntity;
use Doctrine\Common\Collections\ArrayCollection;
use Ginger\Job\Run\LoggerInterface;

/**
 * Description of JobRepository
 *
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 * @copyright (c) 2013, Alexander Miertsch
 */
class JobRepository extends EntityRepository implements JobLoaderInterface
{
    /**
     *
     * @var LoggerInterface
     */
    protected $logger;

    /**
     *
     * @var SourceLoaderInterface
     */
    protected $sourceLoader;

    /**
     *
     * @var TargetLoaderInterface
     */
    protected $targetLoader;

    /**
     *
     * @var MapperLoaderInterface
     */
    protected $mapperLoader;

    /**
     *
     * @var FeatureLoaderInterface
     */
    protected $featureLoader;

    protected $translator;

    /**
     *
     * @var Connector
     */
    protected $connector;

    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function setConnector(Connector $connector)
    {
        $this->connector = $connector;
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

    public function setTranslator($translator)
    {
        $this->translator = $translator;
    }

    public function getJobNames()
    {
        $query = $this->createQueryBuilder('j');

        $query->select('j.name');

        $res = $query->getQuery()->getResult(AbstractQuery::HYDRATE_ARRAY);

        $names = array();

        foreach ($res as $nameArr) {
            $names[] = $nameArr['name'];
        }

        return $names;
    }

    public function getJobs()
    {
        $jobEntities = $this->findAll();

        $jobs = array();

        foreach($jobEntities as $jobEntity) {
            $jobs[] = $this->hydrateJob($jobEntity);
        }

        return $jobs;
    }

    public function loadJob($name)
    {
        $jobEntity = $this->findOneByName($name);

        if (!is_null($jobEntity)) {
            return $this->hydrateJob($jobEntity);
        } else {
            return null;
        }
    }

    public function saveJob(Job $job)
    {
        $jobEntity = $this->findOneByName($job->getName());
        $taskCollection = new ArrayCollection();

        if ($jobEntity) {
            $taskRepo = $this->getEntityManager()->getRepository('GingerORM\Entity\Task');
            $newTaskIds = array();
            foreach ($job->getTasks() as $task) {
                if (is_null($task->getId())) {
                    $taskEntity = null;
                } else {
                    $taskEntity = $taskRepo->find($task->getId());
                }

                if (is_null($taskEntity)) {
                    $taskEntity = new TaskEntity();
                } else {
                    $newTaskIds[] = $task->getId();
                }

                $taskEntity->setJob($jobEntity);
                $taskEntity->setConfig($task->serialize());
                $taskCollection->add($taskEntity);
            }

            $taskEntitiesToRemove = $jobEntity->getTasks()->filter(function($task) use ($newTaskIds) {
                return !in_array($task->getId(), $newTaskIds);
            });

            foreach($taskEntitiesToRemove as $removedTaskEntity) {
                $this->getEntityManager()->remove($removedTaskEntity);
            }

        } else {
            $jobEntity = new JobEntity();
            $jobEntity->setName($job->getName());
            $jobEntity->setDescription($job->getDescription());

            foreach ($job->getTasks() as $task) {
                $taskEntity = new TaskEntity();
                $taskEntity->setJob($jobEntity);
                $taskEntity->setConfig($task->serialize());
                $taskCollection->add($taskEntity);
            }

            $this->getEntityManager()->persist($jobEntity);
        }

        $jobEntity->setTasks($taskCollection);
        $jobEntity->setBreakOnFailure($job->getBreakOnFailure());
        $jobEntity->setDescription($job->getDescription());

        $this->getEntityManager()->flush();

        foreach ($job->getTasks() as $i => $task) {
            $taskEntity = $taskCollection[$i];
            $task->setId($taskEntity->getId());
        }
    }

    public function hasJob($name)
    {
        $names = $this->getJobNames();

        return in_array($name, $names);
    }

    public function deleteJob($name)
    {
        $jobEntity = $this->findOneByName($name);

        if ($jobEntity) {
            $this->getEntityManager()->remove($jobEntity);
            $this->getEntityManager()->flush();
        } else {
            return false;
        }

        return true;
    }

    protected function hydrateJob(JobEntity $jobEntity)
    {
        $job = new Job($jobEntity->getName());
        $job->setDescription($jobEntity->getDescription());
        $job->setLogger($this->logger);
        $job->setConcector($this->connector);
        $job->setTranslator($this->translator);

        $taskArr = array();

        foreach ($jobEntity->getTasks() as $taskEntity) {
            $task = new JobTask();
            $task->setId($taskEntity->getId());
            $task->setSourceLoader($this->sourceLoader);
            $task->setTargetLoader($this->targetLoader);
            $task->setMapperLoader($this->mapperLoader);
            $task->setFeatureLoader($this->featureLoader);
            $task->unserialize($taskEntity->getConfig());
            $taskArr[] = $task;
        }

        $job->setTasks($taskArr);
        $job->setBreakOnFailure($jobEntity->getBreakOnFailure());

        return $job;
    }
}