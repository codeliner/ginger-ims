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

    public function logMessage($configurationRunId, Job\Run\Message $message)
    {
        $configRunEntity = $this->entityManager
            ->getRepository('Ginger\Entity\ConfigurationRun')->find($configurationRunId);

        if (is_null($configRunEntity)) {
            throw new Job\Run\Exception\InvalidArgumentException(
                'Configuration run ID: '
                . $configurationRunId
                . ' is invalid. Entity can not be found!'
                );
        }

        $messageEntity = new Entity\ConfigurationRunMessage();

        $messageEntity->setText($message->getText());
        $messageEntity->setType($message->getType());
        $messageEntity->setTimestamp($message->getTimestamp());

        $configRunEntity->addConfigurationRunMessage($messageEntity);

        $this->entityManager->flush();
    }

    public function startConfigurationRun($jobRunId, $configurationId, $totalItemCount)
    {
        $jobRunEntity = $this->entityManager->getRepository('Ginger\Entity\JobRun')->find($jobRunId);

        if (is_null($jobRunEntity->getStartTime()) || !is_null($jobRunEntity->getEndTime())) {
            throw new Job\Run\Exception\JobNotStartedException(
                sprintf(
                    'Can not start a config run. No active jobrun found for job "%s"',
                    $jobRunEntity->getName()
                    ));
        }

        $configEntity = $this->entityManager->getRepository('Ginger\Entity\Configuration')->find($configurationId);

        if (is_null($configEntity)) {
            throw new Job\Run\Exception\InvalidArgumentException(
                'Invalid configurationId: ' . $configurationId . '. '
                . 'Configuration can not be found.'
                );
        }

        $configRunEntity = new Entity\ConfigurationRun();

        $configRunEntity->setStartTime(new \DateTime());

        $configRunEntity->setConfiguration($configEntity);

        $configRunEntity->setTotalItemCount($totalItemCount);

        $jobRunEntity->addConfigurationRun($configRunEntity);

        $this->entityManager->flush();

        return $configRunEntity->getId();
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

    public function stopConfigurationRun($configurationRunId, $success, $insertedItemCount)
    {
        $configRunEntity = $this->entityManager
            ->getRepository('Ginger\Entity\ConfigurationRun')->find($configurationRunId);

        if (is_null($configRunEntity)) {
            throw new Job\Run\Exception\InvalidArgumentException(
                'Configuration run ID: '
                . $configurationRunId
                . ' is invalid. Entity can not be found!'
                );
        }

        $configRunEntity->setSuccess($success);
        $configRunEntity->setInsertedItemCount($insertedItemCount);
        $configRunEntity->setEndTime(new \DateTime());

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

        $configurationRuns = array();

        foreach($jobRunEntity->getConfigurationRuns() as $configurationRunEntity) {
            $configurationRuns[] = $this->hydrateConfigurationRun($configurationRunEntity);
        }
        $jobRun->setConfigurationRuns($configurationRuns);

        return $jobRun;
    }

    protected function hydrateConfigurationRun($configurationRunEntity)
    {
        $configurationRun = new Job\Run\ConfigurationRun($configurationRunEntity->getId());
        $configurationRun->setConfigurationId($configurationRunEntity->getConfiguration()->getId());
        $configurationRun->setStartTime($configurationRunEntity->getStartTime());
        $configurationRun->setEndTime($configurationRunEntity->getEndTime());
        $configurationRun->setTotalItemCount($configurationRunEntity->getTotalItemCount());
        $configurationRun->setInsertedItemCount($configurationRunEntity->getInsertedItemCount());
        $configurationRun->setSuccess($configurationRunEntity->getSuccess());
        $messages = array();
        foreach ($configurationRunEntity->getConfigurationRunMessages() as $messageEntity) {
            $messages[] = $this->hydrateConfigurationRunMessage($messageEntity);
        }
        $configurationRun->setMessages($messages);
        return $configurationRun;
    }

    protected function hydrateConfigurationRunMessage($configurationRunMessageEntity)
    {
        $message = new Job\Run\Message($configurationRunMessageEntity->getType());
        $message->setText($configurationRunMessageEntity->getText());
        $message->setTimestamp($configurationRunMessageEntity->getTimestamp());
        return $message;
    }
}