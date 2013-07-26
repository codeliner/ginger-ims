<?php
namespace Ginger\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * Description of ConfigurationRun
 *
 * @ORM\Entity
 * @ORM\Table(name="configuration_run")
 *
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 * @copyright (c) 2013, Alexander Miertsch
 */
class ConfigurationRun
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
     * @ORM\Column(type="integer")
     */
    private $totalItemCount = 0;

    /**
     * @ORM\Column(type="integer")
     */
    private $insertedItemCount = 0;

    /**
     * @ORM\Column(type="boolean")
     */
    private $success = false;

    /**
     * @ORM\ManyToOne(targetEntity="Ginger\Entity\JobRun", cascade={"persist"}, fetch="LAZY", inversedBy="configurations")
     * @ORM\JoinColumn(name="job_run_id", referencedColumnName="id")
     */
    private $jobRun;

    /**
     * @ORM\OneToOne(targetEntity="Ginger\Entity\Configuration")
     */
    private $configuration;

    /**
     * @ORM\OneToMany(targetEntity="Ginger\Entity\ConfigurationRunMessage", mappedBy="configurationRun", cascade={"persist"}, orphanRemoval=true)
     */
    private $configurationRunMessages;

    public function __construct()
    {
        $this->configurationRunMessages = new ArrayCollection();
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

    public function getTotalItemCount()
    {
        return $this->totalItemCount;
    }

    public function setTotalItemCount($totalItemCount)
    {
        $this->totalItemCount = $totalItemCount;
    }

    public function getInsertedItemCount()
    {
        return $this->insertedItemCount;
    }

    public function setInsertedItemCount($insertedItemCount)
    {
        $this->insertedItemCount = $insertedItemCount;
    }

    public function getSuccess()
    {
        return $this->success;
    }

    public function setSuccess($success)
    {
        $this->success = $success;
    }

    public function getJobRun()
    {
        return $this->jobRun;
    }

    public function setJobRun($jobRun)
    {
        $this->jobRun = $jobRun;
    }

    public function getConfiguration()
    {
        return $this->configuration;
    }

    public function setConfiguration($configuration)
    {
        $this->configuration = $configuration;
    }

    public function getConfigurationRunMessages()
    {
        return $this->configurationRunMessages;
    }

    public function setConfigurationRunMessages($configurationRunMessages)
    {
        $this->configurationRunMessages = $configurationRunMessages;
    }

    public function addConfigurationRunMessage($configurationRunMessage)
    {
        $configurationRunMessage->setConfigurationRun($this);
        $this->configurationRunMessages->add($configurationRunMessage);
    }
}