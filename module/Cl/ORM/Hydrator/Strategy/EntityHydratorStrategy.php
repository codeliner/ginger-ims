<?php
namespace Cl\ORM\Hydrator\Strategy;

use Zend\Stdlib\Hydrator\Strategy\StrategyInterface;
use Zend\Stdlib\Hydrator\HydratorInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;
use Cl\Stdlib\ArrayUtils;

/**
 * Description of EntityHydratorStrategy
 *
 * Can be used to hydrate or extract referenced entities with their own hydrator
 * Strategy can be used for single or collection references
 *
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 * @copyright (c) 2013, Alexander Miertsch
 */
class EntityHydratorStrategy implements StrategyInterface
{
    /**
     *
     * @var string
     */
    protected $entityName;

    /**
     *
     * @var HydratorInterface
     */
    protected $entityHydrator;

    /**
     *
     * @var EntityManager
     */
    protected $entityManager;

    public function __construct($entityName)
    {
        $this->entityName = $entityName;
    }


    public function setEntityHydrator($entityHydrator)
    {
        $this->entityHydrator = $entityHydrator;
    }


    public function setEntityManager($entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function extract($value)
    {
        $result = array();

        if (ArrayUtils::isList($value, true)) {
            $entities = array();

            foreach ($value as $entity) {
                $entities[] = $this->entityHydrator->extract($entity);
            }

            $result = $entities;
        } else {
            if (is_object($value)) {
                $result = $this->entityHydrator->extract($value);
            } else {
                $result = $value;
            }
        }

        return $result;
    }

    public function hydrate($value)
    {
        $result = null;

        if (!is_null($value)) {
            if (ArrayUtils::isList($value, true)) {
                $result = new ArrayCollection();

                foreach ($value as $entityData) {
                    $result[] = $this->hydrateEntityData($entityData);
                }
            } else {
                $result = $this->hydrateEntityData($value);
            }
        }

        return $result;
    }

    protected function hydrateEntityData($data)
    {
        $entity = null;

        if (isset($data['id']))
            $entity = $this->entityManager->getRepository($this->entityName)->find($data['id']);

        if (is_null($entity)) {
            $entity = new $this->entityName();
            $this->entityManager->persist($entity);
            $this->entityHydrator->hydrate($data, $entity);
        }

        return $entity;
    }
}
