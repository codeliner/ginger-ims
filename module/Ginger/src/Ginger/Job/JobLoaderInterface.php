<?php
namespace Ginger\Job;
/**
 * Description of JobLoaderInterface
 *
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 * @copyright (c) 2013, Alexander Miertsch
 */
interface JobLoaderInterface
{
    /**
     * Load a job by it's name
     *
     * @param string $name
     *
     * @return Job
     */
    public function loadJob($name);

    /**
     * Save a job with it's tasks
     *
     * Set the generated task ids on the provided instances
     * Remove tasks, that are no longer connected with the job
     *
     * @param Job $job
     */
    public function saveJob(Job $job);

    /**
     * Return a list of all job names
     *
     * @return array List of all job names
     */
    public function getJobNames();
    
    /**
     * Return a list of all jobs
     * 
     * @return Job[]
     */
    public function getJobs();

    /**
     * Check if a job with the given name exists
     * 
     * @param string $name
     * 
     * @return boolean
     */
    public function hasJob($name);

    /**
     * Delete a job (and all dependencies) by it's name
     * 
     * @param string $name
     */
    public function deleteJob($name);
}