<?php
namespace Cl\ORM\Hydrator\Strategy;

use Zend\Stdlib\Hydrator\Strategy\StrategyInterface;
use Doctrine\ORM\EntityManager;

/**
 * Description of EntityIdHydratorStrategy
 *
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 * @copyright (c) 2013, Alexander Miertsch
 */
class EntityIdStrategy implements StrategyInterface
{
    protected $entityManager;
    
    protected $entityName;
    
    public function __construct($enityName)
    {
        $this->entityName = $enityName;
    }


    public function setEntityManager($entityManager)
    {
        $this->entityManager = $entityManager;
    }

        
    public function extract($value)
    {
        if (is_object($value) && method_exists($value, 'getId'))
            return $value->getId();
        else
            return $value;
    }

    public function hydrate($value)
    {
        if (is_string($value) || is_int($value)) {
            return $this->entityManager->find($this->entityName, $value);
        } else {
            return $value;
        }
    }
}
