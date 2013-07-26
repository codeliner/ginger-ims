<?php
namespace Ginger\Repository;

use Doctrine\ORM\EntityRepository;
use Ginger\Service\Module\ModuleLoaderInterface;
use Ginger\Entity\ModuleConfiguration;
/**
 * Description of ModuleConfigurationRepository
 *
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 * @copyright (c) 2013, Alexander Miertsch
 */
class ModuleConfigurationRepository extends EntityRepository implements ModuleLoaderInterface
{
    public function loadModuleConfig($moduleName)
    {
        $moduleEntiy = $this->getEntity($moduleName);

        if ($moduleEntiy) {
            return json_decode($moduleEntiy->getConfiguration(), true);
        }

        return null;
    }

    public function saveModuleConfig($moduleName, $config)
    {
        $moduleEntity = $this->getEntity($moduleName);

        if (is_null($moduleEntity)) {
            $moduleEntity = new ModuleConfiguration();
            $moduleEntity->setModule($moduleName);
            $this->getEntityManager()->persist($moduleEntity);
        }

        $moduleEntity->setConfiguration(json_encode($config));

        $this->getEntityManager()->flush($moduleEntity);
    }

    protected function getEntity($moduleName)
    {
        return $this->findOneByModule($moduleName);
    }
}