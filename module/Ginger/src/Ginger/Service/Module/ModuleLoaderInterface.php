<?php
namespace Ginger\Service\Module;
/**
 * Description of ModuleLoader
 *
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 * @copyright (c) 2013, Alexander Miertsch
 */
interface ModuleLoaderInterface
{
    /**
     *
     * @param string $moduleName Name of the module
     *
     * @return array
     */
    public function loadModuleConfig($moduleName);

    /**
     *
     * @param string $moduleName Name of the module
     * @param array  $config     Module config as array
     */
    public function saveModuleConfig($moduleName, $config);
}