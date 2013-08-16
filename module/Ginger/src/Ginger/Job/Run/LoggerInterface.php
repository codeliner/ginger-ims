<?php
namespace Ginger\Job\Run;
/**
 * Description of LoggerInterface
 *
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 * @copyright (c) 2013, Alexander Miertsch
 */
interface LoggerInterface
{
    /**
     * Create a new jobrun and set the start time
     *
     * @param string $jobName Name of the Job
     *
     * @return integer JobRun Id
     */
    public function startJobRun($jobName);

    /**
     * Stop the active run and set endTime
     *
     * @param integer $jobRunId Name of the Job
     * @param boolean $success  Status of the run
     *
     * @return void
     */
    public function stopJobRun($jobRunId, $success);

    /**
     * Start task run and set startTime
     *
     * @param string  $jobRunId        Identifier of the Job
     * @param integer $taskId Identifier of the task
     * @param integer $totalItemCount Total count of items, which should be inserted
     *
     * @return integer task run Id
     *
     * @throws Exception\JobNotStartedException if method is called befor {@method startJobRun} is called
     */
    public function startTaskRun($jobRunId, $taskId, $totalItemCount);

    /**
     * Log a message
     *
     * @param integer $taskRunId Identifier of the task run
     * @param Message $message            A run message
     *
     * @return void
     */
    public function logMessage($taskRunId, Message $message);

    /**
     * Stop the active task run and set stopTime
     *
     * @param integer $taskRunId Identifier of the task run
     * @param boolean $success Status of the task run
     * @param integer $insertedItemsCount Count of the inserted items
     *
     * @return void
     */
    public function stopTaskRun($taskRunId, $success, $insertedItemsCount);

    /**
     * Get a JobRun
     *
     * @param int $jobRunId Identifier of the Job run
     *
     * @return JobRun
     */
    public function getJobRun($jobRunId);

    /**
     * Get all previous loged Jobruns of the job
     *
     * @param string  $jobName Name of the job
     * @param integer $max     Maximum number of jobs to return, 
     *                         if it is null, all jobs should be returned
     * @param integer $skip    Skip the number of jobs, 
     *                         useful for paginiation in combination with $max
     * 
     * @return JobRun
     */
    public function getJobRuns($jobName, $max = 0, $skip = 0);

    /**
     * Get count of jobruns for given job
     *
     * @param string $jobName Name of the job
     *
     * @return integer
     */
    public function countJobRuns($jobName);

    /**
     * Delete jobrun by id
     *
     * @param integer $jobRunId
     */
    public function deleteJobRun($jobRunId);

    /**
     * Get the latest jobruns, independent of a job
     *
     * @param integer $maxResults
     *
     * @return JobRun[]
     */
    public function getLatestJobRuns($maxResults = 10);
}