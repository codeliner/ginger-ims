<?php
namespace Cl\Test;

use Doctrine\ORM\EntityManager;

/**
 * Description of DoctrineTestCase
 *
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 * @copyright (c) 2013, Alexander Miertsch
 */
class DoctrineTestCase extends PHPUnitTestCase
{
    protected $entityManager;
    protected $schemaTool;


    /**
     *
     * @return EntityManager
     */
    public function getTestEntityManager()
    {
        if (null === $this->entityManager) {
            $conn = \Doctrine\DBAL\DriverManager::getConnection(array(
                'driver' => 'pdo_sqlite',
                'memory' => true
            ));

            $config = new \Doctrine\ORM\Configuration();

            $config->setAutoGenerateProxyClasses(true);
            $config->setProxyDir(\sys_get_temp_dir());
            $config->setProxyNamespace(get_class($this) . '\Entities');
            $config->setMetadataDriverImpl(
                new \Doctrine\ORM\Mapping\Driver\AnnotationDriver(
                    new \Doctrine\Common\Annotations\IndexedReader(
                        new \Doctrine\Common\Annotations\AnnotationReader()
                    )
                )
            );

            $config->setQueryCacheImpl(new \Doctrine\Common\Cache\ArrayCache());
            $config->setMetadataCacheImpl(new \Doctrine\Common\Cache\ArrayCache());

            $this->entityManager = \Doctrine\ORM\EntityManager::create(array(
                'driver' => 'pdo_sqlite',
                'memory' => true
            ), $config);
        }

        return $this->entityManager;
    }

    public function createEntitySchema($entityNameOrNamespace, $pathToEntityDir = null)
    {
        if (!is_null($pathToEntityDir)) {
            $dir = opendir($pathToEntityDir);

            $entityNameOrNamespace = trim($entityNameOrNamespace, '\\');

            while($file = readdir($dir)) {
                if (0 !== strpos($file, '.')) {
                    $entityClass = $entityNameOrNamespace . '\\' . str_replace('.php', '', $file);
                    $this->createEntitySchema($entityClass);
                }
            }

            return;
        }

        if (null === $this->schemaTool) {
            $this->schemaTool = new \Doctrine\ORM\Tools\SchemaTool($this->getTestEntityManager());
        }
        $schema = $this->getTestEntityManager()->getClassMetadata($entityNameOrNamespace);
        $this->schemaTool->dropSchema(array($schema));
        $this->schemaTool->createSchema(array($schema));
    }
}