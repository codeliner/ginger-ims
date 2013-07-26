<?php
namespace Ginger\Job\Run;
/**
 * Description of JobRun
 *
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 * @copyright (c) 2013, Alexander Miertsch
 */
class JobRun
{
    private $id;

    private $jobName;

    private $startTime;

    private $endTime;

    private $success = false;

    /**
     * List of configuration runs indexed by configurationId
     *
     * @var array
     */
    private $configurationRuns = array();

    public function __construct($id, $jobName)
    {
        $this->id = $id;
        $this->jobName = $jobName;
        $this->startTime = new \DateTime();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getJobName()
    {
        return $this->jobName;
    }

    public function getStartTime()
    {
        return $this->startTime;
    }

    public function setStartTime($startTime)
    {
        $this->startTime = $startTime;
    }

    public function getEndTime()
    {
        return $this->endTime;
    }

    public function setEndTime($endTime)
    {
        $this->endTime = $endTime;
    }

    public function getSuccess()
    {
        return $this->success;
    }

    public function setSuccess($success)
    {
        $this->success = $success;
    }

    public function getConfigurationRuns()
    {
        return $this->configurationRuns;
    }

    public function setConfigurationRuns($configurationRuns)
    {
        $this->configurationRuns = $configurationRuns;
    }

    public function getArrayCopy()
    {
        $data = array(
            'id' => $this->getId(),
            'jobName' => $this->getJobName(),
            'success' => $this->getSuccess(),
        );

        if (is_null($this->getStartTime())) {
            $data['startTime'] = null;
        } else {
            $data['startTime'] = $this->getStartTime()->format('Y-m-d H:i:s');
        }

        if (is_null($this->getEndTime())) {
            $data['endTime'] = null;
        } else {
            $data['endTime'] = $this->getEndTime()->format('Y-m-d H:i:s');
        }
        $configRuns = array();
        foreach($this->getConfigurationRuns() as $configRun) {
            $configRuns[] = $configRun->getArrayCopy();
        }

        $data['configurationRuns'] = $configRuns;

        return $data;
    }
}