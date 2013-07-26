<?php
namespace Ginger\Model\Target;
/**
 * Description of TargetLoaderInterface
 *
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 * @copyright (c) 2013, Alexander Miertsch
 */
interface TargetLoaderInterface
{
    /**
     * Get target info
     *
     * If a target implements \Zend\ServiceManager\ServiceLocatorAwareInterface,
     * the loader should provide the serviceLocator to it
     *
     * @param integer $targetId
     *
     * @return AbstractTarget
     */
    public function getTarget($targetId);

    /**
     * @return AbstractTarget[] List of all registered targets
     */
    public function listTargets();

    /**
     * Register a target in the system
     *
     * @param string $moduleName  Name of the module which registers the element
     * @param string $targetName  Name of the target
     * @param string $targetClass Class of the target or service manager alias
     * @param string $targetLink  Link to target info
     *
     * @return integer targetId
     */
    public function registerTarget($moduleName, $targetName, $targetClass, $targetLink);

    public function unregisterTarget($targetId);
}