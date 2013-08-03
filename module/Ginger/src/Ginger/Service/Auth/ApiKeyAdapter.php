<?php
namespace Ginger\Service\Auth;

use Zend\Authentication\Adapter\AdapterInterface;
use Zend\Authentication\Result as AuthResult;
use Ginger\Model\User\UserLoaderInterface;
/**
 * Description of ApiKeyAdapter
 * 
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 * @copyright (c) 2013, Alexander Miertsch
 */
class ApiKeyAdapter implements AdapterInterface
{
    /**
     *
     * @var UserLoaderInterface
     */
    protected $userLoader;
    
    /**
     *
     * @var string
     */
    protected $apiKey;
    
    /**
     *
     * @var string
     */
    protected $requestUri;
    
    /**
     *
     * @var string
     */
    protected $requestHash;
    
    public function __construct(UserLoaderInterface $userLoader)
    {
        $this->userLoader = $userLoader;
    }
    
    public function setApiKey($apiKey)
    {
        $this->apiKey = $apiKey;
    }

    public function setRequestHash($requestHash)
    {
        $this->requestHash = $requestHash;
    }
    
    public function setRequestUri($requestUri)
    {
        $this->requestUri = $requestUri;
    }
    
    /**
     * Authenticate a client by comparing a hmac hash of the requestUri 
     * with the provided requestHash.
     * 
     * The hash is generated with the secret key assigned to the provided apiKey.
     * 
     * {@inheritdoc}
     */
    public function authenticate()
    {
        $userData = $this->userLoader->loadUserByApiKey($this->apiKey);
        
        if (!$userData) {
            return new AuthResult(AuthResult::FAILURE_IDENTITY_NOT_FOUND, null);
        }
        
        $checkHash = hash_hmac('sha1', $this->requestUri, $userData['secretKey']);
        
        if ($checkHash != $this->requestHash) {
            return new AuthResult(AuthResult::FAILURE_CREDENTIAL_INVALID, null);
        }
        
        return new AuthResult(AuthResult::SUCCESS, $userData);
    }    
}
