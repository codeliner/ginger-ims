<?php
namespace GingerORM\Entity;

use Doctrine\ORM\Mapping as ORM;
/**
 * Description of TaskRunMessage
 *
 * @ORM\Entity
 * @ORM\Table(name="task_run_message")
 *
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 * @copyright (c) 2013, Alexander Miertsch
 */
class TaskRunMessage
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $timestamp;

    /**
     * @ORM\Column(type="string")
     */
    private $type;

    /**
     * @ORM\Column(type="string")
     */
    private $text;

    /**
     * @ORM\ManyToOne(targetEntity="GingerORM\Entity\TaskRun", cascade={"persist", "merge"}, fetch="LAZY")
     * @ORM\JoinColumn(name="task_run_id", referencedColumnName="id")
     */
    private $taskRun;

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getTimestamp()
    {
        return $this->timestamp;
    }

    public function setTimestamp($timestamp)
    {
        $this->timestamp = $timestamp;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setType($type)
    {
        $this->type = $type;
    }

    public function getText()
    {
        return $this->text;
    }

    public function setText($text)
    {
        $this->text = $text;
    }

    public function getTaskRun()
    {
        return $this->taskRun;
    }

    public function setTaskRun($taskRun)
    {
        $this->taskRun = $taskRun;
    }
}