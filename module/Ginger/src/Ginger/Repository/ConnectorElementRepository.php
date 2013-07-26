<?php
namespace Ginger\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\AbstractQuery;
use Ginger\Entity\ConnectorElement;
use Ginger\Model\Source\SourceLoaderInterface;
use Ginger\Model\Target\TargetLoaderInterface;
use Ginger\Model\Mapper\MapperLoaderInterface;
use Ginger\Model\Feature\FeatureLoaderInterface;
use Ginger\Model\Connector\Exception\ElementNameExistException;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
/**
 * Description of ConnectorElementRepository
 *
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 * @copyright (c) 2013, Alexander Miertsch
 */
class ConnectorElementRepository extends EntityRepository
implements SourceLoaderInterface,
    TargetLoaderInterface,
    MapperLoaderInterface,
    FeatureLoaderInterface,
    ServiceLocatorAwareInterface
{
    /**
     * @var ServiceLocatorInterface
     */
    protected $serviceLocator;

    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }

    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
    }

    public function getMapper($mapperId)
    {
        return $this->hydrateElement($this->find($mapperId));
    }

    public function getSource($sourceId)
    {
        return $this->hydrateElement($this->find($sourceId));
    }

    public function getTarget($targetId)
    {
        return $this->hydrateElement($this->find($targetId));
    }

    public function getFeature($featureId)
    {
        return $this->hydrateElement($this->find($featureId));
    }

    public function registerMapper($moduleName, $mapperName, $mapperClass, $mapperLink)
    {
        return $this->createElementEntity('mapper', $moduleName, $mapperName, $mapperClass, $mapperLink);
    }

    public function registerSource($moduleName, $sourceName, $sourceClass, $sourceLink)
    {
        return $this->createElementEntity('source', $moduleName, $sourceName, $sourceClass, $sourceLink);
    }

    public function registerTarget($moduleName, $targetName, $targetClass, $targetLink)
    {
        return $this->createElementEntity('target', $moduleName, $targetName, $targetClass, $targetLink);
    }

    public function registerFeature($moduleName, $featureName, $featureClass, $featureLink)
    {
        return $this->createElementEntity('feature', $moduleName, $featureName, $featureClass, $featureLink);
    }

    public function unregisterMapper($mapperId)
    {
        $this->deleteElementEntity($mapperId);
    }

    public function unregisterSource($sourceId)
    {
        $this->deleteElementEntity($sourceId);
    }

    public function unregisterTarget($targetId)
    {
        $this->deleteElementEntity($targetId);
    }

    public function unregisterFeature($featureId)
    {
        $this->deleteElementEntity($featureId);
    }

    public function listSources()
    {
        $sourceEntities = $this->findBy(array('type' => 'source'));

        $sources = array();

        foreach ($sourceEntities as $sourceEntity) {
            $sources[] = $this->hydrateElement($sourceEntity);
        }

        return $sources;
    }

    public function listTargets()
    {
        $targetEntities = $this->findBy(array('type' => 'target'));

        $targets = array();

        foreach ($targetEntities as $targetEntity) {
            $targets[] = $this->hydrateElement($targetEntity);
        }

        return $targets;
    }

    public function listMappers()
    {
        $mapperEntities = $this->findBy(array('type' => 'mapper'));

        $mappers = array();

        foreach($mapperEntities as $mapperEntity) {
            $mappers[] = $this->hydrateElement($mapperEntity);
        }

        return $mappers;
    }

    public function listFeatures()
    {
        $featureEntities = $this->findBy(array('type' => 'feature'));

        $features = array();

        foreach ($featureEntities as $featureEntity) {
            $features[] = $this->hydrateElement($featureEntity);
        }

        return $features;
    }

    protected function createElementEntity($type, $moduleName, $name, $class, $link)
    {
        $this->testElementName($name, $type);

        $element = new ConnectorElement();
        $element->setName($name);
        $element->setType($type);
        $element->setClass($class);
        $element->setLink($link);
        $element->setModuleName($moduleName);

        $this->getEntityManager()->persist($element);
        $this->getEntityManager()->flush($element);

        return $element->getId();
    }

    protected function deleteElementEntity($id)
    {
        $query = $this->getEntityManager()->createQueryBuilder();

        $query->delete('Ginger\Entity\ConnectorElement', 'e')->where('e.id = :id')->setParameter('id', $id);

        $query->getQuery()->execute();
    }

    protected function hydrateElement($entity)
    {
        if (is_null($entity)) {
            return null;
        }
        $class = $entity->getClass();
        $element = new $class($entity->getId(), $entity->getName(), $entity->getLink(), $entity->getModuleName());

        if ($element instanceof ServiceLocatorAwareInterface) {
            $element->setServiceLocator($this->serviceLocator);
        }

        return $element;
    }

    protected function testElementName($elementName, $type)
    {
        $query = $this->createQueryBuilder('ce');
        $query->select('count(ce.id)')
            ->where('ce.name = :name')
            ->andWhere('ce.type = :type')
            ->setParameter('name', $elementName)
            ->setParameter('type', $type);

        $check = $query->getQuery()->getResult(AbstractQuery::HYDRATE_SINGLE_SCALAR);

        if ($check == 1) {
            throw new ElementNameExistException("An element with the name: '" . $elementName . "' is already registered");
        }
    }
}
