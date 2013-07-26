<?php
namespace Ginger\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * Description of JobRun
 *
 * @ORM\Entity
 * @ORM\Table(name="job_run")
 *
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 * @copyright (c) 2013, Alexander Miertsch
 */
class JobRun
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $startTime;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $endTime;

    /**
     * @ORM\Column(type="boolean")
     */
    private $success = false;

    /**
     * @ORM\ManyToOne(targetEntity="Ginger\Entity\Job", cascade={"persist", "merge"}, fetch="LAZY")
     */
    private $job;

    /**
     * @ORM\OneToMany(targetEntity="Ginger\Entity\ConfigurationRun", mappedBy="jobRun", cascade={"persist"}, orphanRemoval=true)
     */
    private $configurationRuns;

    public function __construct()
    {
        $this->configurationRuns = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
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

    public function getJob()
    {
        return $this->job;
    }

    public function setJob($job)
    {
        $this->job = $job;
    }

    public function getConfigurationRuns()
    {
        return $this->configurationRuns;
    }

    public function setConfigurationRuns($configurationRuns)
    {
        $this->configurationRuns = $configurationRuns;
    }

    public function addConfigurationRun($configurationRun)
    {
        $configurationRun->setJobRun($this);
        $this->configurationRuns->add($configurationRun);
    }
}