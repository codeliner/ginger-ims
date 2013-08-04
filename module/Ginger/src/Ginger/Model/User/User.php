<?php
namespace Ginger\Model\User;

use Zend\Permissions\Acl\Role\RoleInterface;
/**
 * Description of User
 *
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 * @copyright (c) 2013, Alexander Miertsch
 */
class User implements RoleInterface
{
    const ACL_ROLE_PREFIX = 'user_';
    
    protected $id;
    
    protected $apiKey;
    
    protected $secretKey;
    
    protected $lastname;
    
    protected $firstname;
    
    protected $email;
    
    protected $isAdmin = false;


    public function __construct($id)
    {
        $this->id = $id;
    }


    public function getId()
    {
        return $this->id;
    }
    
    public function getRoleId()
    {
        return static::ACL_ROLE_PREFIX . $this->getId();
    }
    
    public function getApiKey()
    {
        return $this->apiKey;
    }

    public function getSecretKey()
    {
        return $this->secretKey;
    }

    public function getLastname()
    {
        return $this->lastname;
    }

    public function getFirstname()
    {
        return $this->firstname;
    }

    public function getEmail()
    {
        return $this->email;
    }
    
    public function isAdmin()
    {
        return $this->isAdmin;
    }

    public function exchangeArray(array $data)
    {
        foreach ($data as $key => $value) {
            if ($key != 'id') {
                $this->{$key} = $value;
            }
        }
    }
}
