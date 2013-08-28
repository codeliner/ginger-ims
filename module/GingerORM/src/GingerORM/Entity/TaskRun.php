<?php
namespace GingerORM\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * Description of TaskRun
 *
 * @ORM\Entity
 * @ORM\Table(name="task_run")
 *
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 * @copyright (c) 2013, Alexander Miertsch
 */
class TaskRun
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
     * @ORM\ManyToOne(targetEntity="GingerORM\Entity\JobRun", cascade={"persist"}, fetch="LAZY", inversedBy="tasks")
     * @ORM\JoinColumn(name="job_run_id", referencedColumnName="id")
     */
    private $jobRun;

    /**
     * @ORM\OneToOne(targetEntity="GingerORM\Entity\Task")
     */
    private $task;

    /**
     * @ORM\OneToMany(targetEntity="GingerORM\Entity\TaskRunMessage", mappedBy="taskRun", cascade={"persist"}, orphanRemoval=true)
     */
    private $taskRunMessages;

    public function __construct()
    {
        $this->taskRunMessages = new ArrayCollection();
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

    public function getTask()
    {
        return $this->task;
    }

    public function setTask($task)
    {
        $this->task = $task;
    }

    public function getTaskRunMessages()
    {
        return $this->taskRunMessages;
    }

    public function setTaskRunMessages($taskRunMessages)
    {
        $this->taskRunMessages = $taskRunMessages;
    }
    
    public function addTaskRunMessage($taskRunMessage)
    {
        $taskRunMessage->setTaskRun($this);
        $this->taskRunMessages->add($taskRunMessage);
    }
}