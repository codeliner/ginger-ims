<?php
namespace SqlConnect\Service\Db;

use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Query\QueryBuilder;
use Ginger\Service\Module\ModuleLoaderInterface;
/**
 * Description of AdapterFactory
 *
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 * @copyright (c) 2013, Alexander Miertsch
 */
class AdapterFactory
{
    /**
     *
     * @var ModuleLoaderInterface
     */
    protected $moduleLoader;

    public function getModuleLoader()
    {
        return $this->moduleLoader;
    }

    public function setModuleLoader($moduleLoader)
    {
        $this->moduleLoader = $moduleLoader;
    }


    public function factory($connection, $connectionConfig = null)
    {
        if (is_null($connectionConfig)) {
            $config = $this->moduleLoader->loadModuleConfig('SqlConnect');

            $connectionConfig = $config['connections'];

            if (!isset($connectionConfig[$connection])) {
                throw new Exception\InvalidConfigurationException(sprintf('Configuration for Connection "%s" is missing.', $connection));
            }

            $connectionConfig = $connectionConfig[$connection]['params'];
        }

        $connectionConfig['driverClass'] = $this->getDriverClass($connectionConfig['driverClass']);

        $conn = DriverManager::getConnection($connectionConfig);

        return new QueryBuilder($conn);


    }

    protected function getDriverClass($driverClassAlias)
    {
        return 'Doctrine\DBAL\Driver\\' . $driverClassAlias . '\Driver';
    }
}