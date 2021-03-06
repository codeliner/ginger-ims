<?php
namespace GingerORM\Entity;

use Doctrine\ORM\Mapping as ORM;
/**
 * Description of Task
 *
 * @ORM\Entity
 * @ORM\Table(name="task")
 *
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 * @copyright (c) 2013, Alexander Miertsch
 */
class Task
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     */
    private $config;

    /**
     * @ORM\ManyToOne(targetEntity="GingerORM\Entity\Job", fetch="LAZY")
     */
    private $job;

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getConfig()
    {
        return $this->config;
    }

    public function setConfig($config)
    {
        $this->config = $config;
    }

    public function getJob()
    {
        return $this->job;
    }

    public function setJob($job)
    {
        $this->job = $job;
    }
}