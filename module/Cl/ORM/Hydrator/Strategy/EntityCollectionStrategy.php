<?php
namespace Cl\ORM\Hydrator\Strategy;

use Zend\Stdlib\Hydrator\Strategy\StrategyInterface;
use Doctrine\ORM\EntityManager;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Description of EntityCollectionHydratorStrategy
 * 
 * The strategy can be used to extract and hydrate referenced entities, that are
 * stored in a collection with numeric index. The entities must provide a getId() method!
 *
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 * @copyright (c) 2013, Alexander Miertsch
 */
class EntityCollectionStrategy implements StrategyInterface
{
    /**
     * Name of the Entity, that is stored in the collection
     * 
     * @var string
     */
    protected $entityName;
    
    public function __construct($entityName)
    {
        $this->entityName = $entityName;
    }
    
    /**
     *
     * @var EntityManager
     */
    protected $entityManager;

    public function setEntityManager($entityManager)
    {
        $this->entityManager = $entityManager;
    }
    
    public function extract($value)
    {
        $collection = array();
        
        if ($value instanceof Collection) {
            foreach ($value as $entity) {
                $collection[] = $entity->getId();
            }
        }
        
        return $collection;
    }

    function hydrate($value)
    {
        $collection = new ArrayCollection();
        
        if (is_array($value)) {
            foreach ($value as $id) {
                $entity = $this->entityManager->find($this->entityName, $id);
                
                if (null !== $entity) {
                    $collection->add($entity);
                }
            }
        }
        
        return $collection;
    }
}