<?php
namespace Ginger\Entity;

use Doctrine\ORM\Mapping as ORM;
/**
 * Description of Configuration
 *
 * @ORM\Entity
 * @ORM\Table(name="configuration")
 *
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 * @copyright (c) 2013, Alexander Miertsch
 */
class Configuration
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
     * @ORM\ManyToOne(targetEntity="Ginger\Entity\Job", fetch="LAZY")
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