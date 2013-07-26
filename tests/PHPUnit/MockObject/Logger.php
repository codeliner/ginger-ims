<?php
namespace MockObject;

use Ginger\Job\Run\LoggerInterface;
use Ginger\Job\Run\JobRun;
use Ginger\Job\Run\ConfigurationRun;
use Ginger\Job\Run\Message;
/**
 * Description of Logger
 *
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 * @copyright (c) 2013, Alexander Miertsch
 */
class Logger implements LoggerInterface
{
    protected $jobRuns = array();

    protected $configurationRuns = array();

    protected $jobConfigMap = array();

    public function getJobRun($jobRunId)
    {
        $jobRun = $this->jobRuns[$jobRunId];

        $configRuns = array();
        foreach ($this->jobConfigMap[$jobRunId] as $configRunId) {
            $configRuns[] = $this->configurationRuns[$configRunId];
        }

        $jobRun->setConfigurationRuns($configRuns);
        return $jobRun;
    }

    public function getJobRuns($jobName, $max = 0, $skip = 0)
    {
        if ($max > 0) {
            $jobRuns = array_slice($this->jobRuns, $skip, $max);
        } else {
            $jobRuns = $this->jobRuns;
        }
        return $jobRuns;
    }

    public function logMessage($configurationRunId, Message $message)
    {
        $configRun = $this->configurationRuns[$configurationRunId];
        $configRun->addMessage($message);
    }

    public function startConfigurationRun($jobRunId, $configurationId, $totalItemCount)
    {
        $configRunId = count($this->configurationRuns) + 1;
        $configRun = new ConfigurationRun($configRunId);
        $configRun->setConfigurationId($configurationId);
        $configRun->setTotalItemCount($totalItemCount);
        $configurationRunId = count($this->configurationRuns);
        $this->configurationRuns[$configurationRunId] = $configRun;
        $this->jobConfigMap[$jobRunId][] = $configurationRunId;
        return $configurationRunId;
    }

    public function startJobRun($jobName)
    {
        $jobRunId = count($this->jobRuns);
        $jobRun = new JobRun($jobRunId, $jobName);
        $this->jobRuns[$jobRunId] = $jobRun;
        $this->jobConfigMap[$jobRunId] = array();
        return $jobRunId;
    }

    public function stopConfigurationRun($configurationRunId, $success, $insertedItemsCount)
    {
        $configRun = $this->configurationRuns[$configurationRunId];
        $configRun->setSuccess($success);
        $configRun->setInsertedItemCount($insertedItemsCount);
        $configRun->setEndTime(new \DateTime());
    }

    public function stopJobRun($jobRunId, $success)
    {
        $jobRun = $this->jobRuns[$jobRunId];
        $jobRun->setSuccess($success);
        $jobRun->setEndTime(new \DateTime());
    }

    public function countJobRuns($jobName)
    {
        return count($this->jobRuns);
    }

    public function deleteJobRun($jobRunId)
    {
        unset($this->jobRuns[$jobRunId]);
        return true;
    }

    public function getLatestJobRuns($maxResults = 10)
    {
        return $this->jobRuns;
    }
}