<?php
namespace MockObject;

use Ginger\Job\Run\LoggerInterface;
use Ginger\Job\Run\JobRun;
use Ginger\Job\Run\TaskRun;
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

    protected $taskRuns = array();

    protected $jobTaskMap = array();

    public function getJobRun($jobRunId)
    {
        $jobRun = $this->jobRuns[$jobRunId];

        $taskRuns = array();
        foreach ($this->jobTaskMap[$jobRunId] as $taskRunId) {
            $taskRuns[] = $this->taskRuns[$taskRunId];
        }

        $jobRun->setTaskRuns($taskRuns);
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

    public function logMessage($taskRunId, Message $message)
    {
        $taskRun = $this->taskRuns[$taskRunId];
        $taskRun->addMessage($message);
    }

    public function startTaskRun($jobRunId, $taskId, $totalItemCount)
    {
        $taskRunId = count($this->taskRuns) + 1;
        $taskRun = new TaskRun($taskRunId);
        $taskRun->setTaskId($taskRunId);
        $taskRun->setTotalItemCount($totalItemCount);
        $taskRunId = count($this->taskRuns);
        $this->taskRuns[$taskRunId] = $taskRun;
        $this->jobTaskMap[$jobRunId][] = $taskRunId;
        return $taskRunId;
    }

    public function startJobRun($jobName)
    {
        $jobRunId = count($this->jobRuns);
        $jobRun = new JobRun($jobRunId, $jobName);
        $this->jobRuns[$jobRunId] = $jobRun;
        $this->taskRuns[$jobRunId] = array();
        return $jobRunId;
    }

    public function stopTaskRun($taskRunId, $success, $insertedItemsCount)
    {
        $taskRun = $this->taskRuns[$taskRunId];
        $taskRun->setSuccess($success);
        $taskRun->setInsertedItemCount($insertedItemsCount);
        $taskRun->setEndTime(new \DateTime());
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