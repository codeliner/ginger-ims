<?php
namespace Ginger\Entity;

use Doctrine\ORM\Mapping as ORM;
/**
 * Description of Permission
 * 
 * @ORM\Entity
 * @ORM\Table(name="permission") 
 * 
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 * @copyright (c) 2013, Alexander Miertsch
 */
class Permission
{
    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Ginger\Entity\User")
     */
    private $user;
    
    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Ginger\Entity\Job")
     */
    private $job;
    
    /**
     * @ORM\Column(type="boolean")
     */
    private $read;
    
    /**
     * @ORM\Column(type="boolean")
     */
    private $write;
    
    /**
     * @ORM\Column(type="boolean")
     */
    private $execute;
    
    public function __construct(User $user, Job $job)
    {
        $this->user = $user;
        $this->job = $job;
    }
    
    public function getUser()
    {
        return $this->user;
    }

    public function setUser($user)
    {
        $this->user = $user;
    }

    public function getJob()
    {
        return $this->job;
    }

    public function setJob($job)
    {
        $this->job = $job;
    }

    public function getRead()
    {
        return $this->read;
    }

    public function setRead($read)
    {
        $this->read = $read;
    }

    public function getWrite()
    {
        return $this->write;
    }

    public function setWrite($write)
    {
        $this->write = $write;
    }

    public function getExecute()
    {
        return $this->execute;
    }

    public function setExecute($execute)
    {
        $this->execute = $execute;
    }
}
