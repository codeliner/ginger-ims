<?php
namespace MockObject;

use Ginger\Job\JobLoaderInterface;
use Ginger\Job\Job;
/**
 * Description of JobLoader
 *
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 * @copyright (c) 2013, Alexander Miertsch
 */
class JobLoader implements JobLoaderInterface
{
    protected $jobNames = array();
    
    protected $jobs = array();


    public function setJobNames($jobNames)
    {
        $this->jobNames = $jobNames;
    }
    
    public function getJobNames()
    {
        return $this->jobNames;
    }
    
    public function setJobs(array $jobs)
    {
        $this->jobs = $jobs;
    }

    public function loadJob($name)
    {
        if (isset($this->jobs[$name])) {
            return $this->jobs[$name];
        }
        
        return null;
    }

    public function saveJob(Job $job)
    {
        $this->jobs[$job->getName()] = $job;
    }

    public function hasJob($name)
    {
        return isset($this->jobs[$name]);
    }

    public function getJobs()
    {
        return $this->jobs;
    }

    public function deleteJob($name)
    {
        if (isset($this->jobs[$name])) {
            unset($this->jobs[$name]);
        }
    }
}