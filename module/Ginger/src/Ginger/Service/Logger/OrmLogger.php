<?php
namespace Ginger\Service\Logger;

use Ginger\Entity;
use Ginger\Job;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\AbstractQuery;

/**
 * Description of OrmLogger
 *
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 * @copyright (c) 2013, Alexander Miertsch
 */
class OrmLogger implements Job\Run\LoggerInterface
{
    /**
     * @var EntityManager
     */
    protected $entityManager;

    public function setEntityManager($entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getJobRun($jobRunId)
    {
        $jobRunEntity = $this->entityManager->getRepository('Ginger\Entity\JobRun')->find($jobRunId);

        if (is_null($jobRunEntity)) {
            return null;
        } else {
            return $this->hydrateJobRun($jobRunEntity);
        }
    }

    public function getJobRuns($jobName, $max = 0, $skip = 0)
    {
        if ($max == 0) {
            $max = null;
        }

        if ($skip == 0) {
            $skip = null;
        }

        $jobEntity = $this->entityManager->getRepository('Ginger\Entity\Job')->findOneByName($jobName);

        $jobRunEntitys = $this->entityManager->getRepository('Ginger\Entity\JobRun')
            ->findBy(array('job' => $jobEntity), array('startTime' => 'DESC'), $max, $skip);

        $jobRuns = array();

        foreach ($jobRunEntitys as $jobRunEntity) {
            $jobRuns[] = $this->hydrateJobRun($jobRunEntity);
        }

        return $jobRuns;
    }

    public function countJobRuns($jobName, $successFilter = null)
    {
        $query = $this->entityManager->getRepository('Ginger\Entity\JobRun')->createQueryBuilder('jr');
        $query->select('count(jr.id)')
            ->join('jr.job', 'j')
            ->where('j.name = :jobname')
            ->setParameter('jobname', $jobName);

        if (!is_null($successFilter)) {
            $query->andWhere('jr.success = :success')
                ->setParameter('success', $successFilter);
        }

        return $query->getQuery()->getResult(AbstractQuery::HYDRATE_SINGLE_SCALAR);
    }

    public function logMessage($taskRunId, Job\Run\Message $message)
    {
        $taskRunEntity = $this->entityManager
            ->getRepository('Ginger\Entity\TaskRun')->find($taskRunId);

        if (is_null($taskRunEntity)) {
            throw new Job\Run\Exception\InvalidArgumentException(
                'task run ID: '
                . $taskRunId
                . ' is invalid. Entity can not be found!'
                );
        }

        $messageEntity = new Entity\TaskRunMessage();

        $messageEntity->setText($message->getText());
        $messageEntity->setType($message->getType());
        $messageEntity->setTimestamp($message->getTimestamp());

        $taskRunEntity->addTaskRunMessage($messageEntity);

        $this->entityManager->flush();
    }

    public function startTaskRun($jobRunId, $taskId, $totalItemCount)
    {
        $jobRunEntity = $this->entityManager->getRepository('Ginger\Entity\JobRun')->find($jobRunId);

        if (is_null($jobRunEntity->getStartTime()) || !is_null($jobRunEntity->getEndTime())) {
            throw new Job\Run\Exception\JobNotStartedException(
                sprintf(
                    'Can not start a task run. No active jobrun found for job "%s"',
                    $jobRunEntity->getName()
                    ));
        }

        $taskEntity = $this->entityManager->getRepository('Ginger\Entity\Task')->find($taskId);

        if (is_null($taskEntity)) {
            throw new Job\Run\Exception\InvalidArgumentException(
                'Invalid taskId: ' . $taskId . '. '
                . 'Task can not be found.'
                );
        }

        $taskRunEntity = new Entity\TaskRun();

        $taskRunEntity->setStartTime(new \DateTime());

        $taskRunEntity->setTask($taskEntity);

        $taskRunEntity->setTotalItemCount($totalItemCount);

        $jobRunEntity->addTaskRun($taskRunEntity);

        $this->entityManager->flush();

        return $taskRunEntity->getId();
    }

    public function startJobRun($jobName)
    {
        $jobEntity = $this->entityManager->getRepository('Ginger\Entity\Job')->findOneByName($jobName);

        if (is_null($jobEntity)) {
            throw new Job\Run\Exception\JobNotStartedException(
                'Could not start a run for job '
                . $jobName . '! Job can not be found!'
                );
        }

        $jobRunEntity = new Entity\JobRun();

        $jobRunEntity->setJob($jobEntity);
        $jobRunEntity->setStartTime(new \DateTime());
        $this->entityManager->persist($jobRunEntity);
        $this->entityManager->flush($jobRunEntity);

        return $jobRunEntity->getId();
    }

    public function stopTaskRun($taskRunId, $success, $insertedItemCount)
    {
        $taskRunEntity = $this->entityManager
            ->getRepository('Ginger\Entity\TaskRun')->find($taskRunId);

        if (is_null($taskRunEntity)) {
            throw new Job\Run\Exception\InvalidArgumentException(
                'Task run ID: '
                . $taskRunId
                . ' is invalid. Entity can not be found!'
                );
        }

        $taskRunEntity->setSuccess($success);
        $taskRunEntity->setInsertedItemCount($insertedItemCount);
        $taskRunEntity->setEndTime(new \DateTime());

        $this->entityManager->flush();
    }

    public function stopJobRun($jobRunId, $success)
    {
        $jobRunEntity = $this->entityManager->getRepository('Ginger\Entity\JobRun')->find($jobRunId);
        $jobRunEntity->setEndTime(new \DateTime());
        $jobRunEntity->setSuccess($success);
        $this->entityManager->flush();
    }

    public function deleteJobRun($jobRunId)
    {
        $jobRunEntity = $this->entityManager->getRepository('Ginger\Entity\JobRun')->find($jobRunId);

        if ($jobRunEntity) {
            $this->entityManager->remove($jobRunEntity);
            $this->entityManager->flush();
            return true;
        }

        return false;
    }

    public function getLatestJobRuns($maxResults = 10)
    {
        $jobRunEntitys = $this->entityManager->getRepository('Ginger\Entity\JobRun')
            ->findBy(array(), array('startTime' => 'DESC'), $maxResults);

        $jobRuns = array();

        foreach ($jobRunEntitys as $jobRunEntity) {
            $jobRuns[] = $this->hydrateJobRun($jobRunEntity);
        }

        return $jobRuns;
    }

    protected function hydrateJobRun($jobRunEntity)
    {
        $jobRun = new Job\Run\JobRun($jobRunEntity->getId(), $jobRunEntity->getJob()->getName());
        $jobRun->setStartTime($jobRunEntity->getStartTime());
        $jobRun->setEndTime($jobRunEntity->getEndTime());
        $jobRun->setSuccess($jobRunEntity->getSuccess());

        $taskRuns = array();

        foreach($jobRunEntity->getTaskRuns() as $taskRunEntity) {
            $taskRuns[] = $this->hydrateTaskRun($taskRunEntity);
        }
        $jobRun->setTaskRuns($taskRuns);

        return $jobRun;
    }

    protected function hydrateTaskRun($taskRunEntity)
    {
        $taskRun = new Job\Run\TaskRun($taskRunEntity->getId());
        $taskRun->setTaskId($taskRunEntity->getTask()->getId());
        $taskRun->setStartTime($taskRunEntity->getStartTime());
        $taskRun->setEndTime($taskRunEntity->getEndTime());
        $taskRun->setTotalItemCount($taskRunEntity->getTotalItemCount());
        $taskRun->setInsertedItemCount($taskRunEntity->getInsertedItemCount());
        $taskRun->setSuccess($taskRunEntity->getSuccess());
        $messages = array();
        foreach ($taskRunEntity->getTaskRunMessages() as $messageEntity) {
            $messages[] = $this->hydrateTaskRunMessage($messageEntity);
        }
        $taskRun->setMessages($messages);
        return $taskRun;
    }

    protected function hydrateTaskRunMessage($taskRunMessageEntity)
    {
        $message = new Job\Run\Message($taskRunMessageEntity->getType());
        $message->setText($taskRunMessageEntity->getText());
        $message->setTimestamp($taskRunMessageEntity->getTimestamp());
        return $message;
    }
}