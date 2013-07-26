<?php
namespace Ginger\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * Description of Job
 *
 * @ORM\Entity(repositoryClass="Ginger\Repository\JobRepository")
 * @ORM\Table(name="job")
 *
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 * @copyright (c) 2013, Alexander Miertsch
 */
class Job
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100, unique=true, nullable=false)
     */
    private $name;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="boolean")
     */
    private $breakOnFailure = true;

    /**
     * @ORM\OneToMany(targetEntity="Ginger\Entity\Configuration", mappedBy="job", cascade={"all"}, orphanRemoval=true)
     */
    private $configurations;

    /**
     * @ORM\OneToMany(targetEntity="Ginger\Entity\JobRun", mappedBy="job", cascade={"persist"}, orphanRemoval=true)
     * @ORM\OrderBy({"startTime" = "DESC"})
     */
    private $jobRuns;

    public function __construct()
    {
        $this->configurations = new ArrayCollection();
        $this->jobRuns = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function getBreakOnFailure()
    {
        return $this->breakOnFailure;
    }

    public function setBreakOnFailure($breakOnFailure)
    {
        $this->breakOnFailure = $breakOnFailure;
    }


    public function getConfigurations()
    {
        return $this->configurations;
    }

    public function setConfigurations($configurations)
    {
        $this->configurations = $configurations;
    }

    public function getJobRuns()
    {
        return $this->jobRuns;
    }

    public function setJobRuns($jobRuns)
    {
        $this->jobRuns = $jobRuns;
    }

    public function addJobRun($jobRun)
    {
        $this->jobRuns->add($jobRun);
    }

    public function removeJobRun($jobRun)
    {
        $this->jobRuns->removeElement($jobRun);
    }
}