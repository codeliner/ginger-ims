<?php
namespace Ginger\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * Description of User
 * 
 * @ORM\Entity(repositoryClass="Ginger\Repository\UserRepository")
 * @ORM\Table(name="user") 
 * 
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 * @copyright (c) 2013, Alexander Miertsch
 */
class User
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;
    
    /**
     * @ORM\Column(type="string", length=32, unique=true, nullable=false)
     */
    private $apiKey;
    
    /**
     * @ORM\Column(type="string", nullable=false)
     */
    private $secretKey;
    
    /**
     * @ORM\Column(type="string", nullable=false)
     */
    private $lastname;
    
    /**
     * @ORM\Column(type="string", nullable=false)
     */
    private $firstname;
    
    /**
     * @ORM\Column(type="string", nullable=false)
     */
    private $email;
    
    /**
     * @ORM\Column(type="boolean")
     */
    private $isAdmin;
    
    /**
     * @ORM\OneToMany(targetEntity="Ginger\Entity\Permission", mappedBy="user")
     */
    private $permissions;
    
    public function __construct()
    {
        $this->permissions = new ArrayCollection();
    }
    
    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getApiKey()
    {
        return $this->apiKey;
    }

    public function setApiKey($apiKey)
    {
        $this->apiKey = $apiKey;
    }

    public function getSecretKey()
    {
        return $this->secretKey;
    }

    public function setSecretKey($secretKey)
    {
        $this->secretKey = $secretKey;
    }

    public function getLastname()
    {
        return $this->lastname;
    }

    public function setLastname($lastname)
    {
        $this->lastname = $lastname;
    }

    public function getFirstname()
    {
        return $this->firstname;
    }

    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function getIsAdmin()
    {
        return $this->isAdmin;
    }

    public function setIsAdmin($isAdmin)
    {
        $this->isAdmin = $isAdmin;
    }
    
    public function getPermissions()
    {
        return $this->permissions;
    }

    public function setPermissions($permissions)
    {
        $this->permissions = $permissions;
    }
}
