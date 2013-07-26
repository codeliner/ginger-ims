<?php
namespace Ginger\Model\Source;
/**
 * Description of SourceLoaderInterface
 *
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 * @copyright (c) 2013, Alexander Miertsch
 */
interface SourceLoaderInterface
{
    /**
     * Get source info
     *
     * If a source implements \Zend\ServiceManager\ServiceLocatorAwareInterface,
     * the loader should provide the serviceLocator to it
     *
     * @param integer $sourceId
     *
     * @return AbstractSource
     */
    public function getSource($sourceId);

    /**
     * @return AbstractSource[] List of all registered sources
     */
    public function listSources();

    /**
     * Register a source in the system
     *
     * @param string $moduleName  Name of the module which registers the element
     * @param string $sourceName  Name of the source
     * @param string $sourceClass Class of the source or service manager alias
     * @param string $sourceLink  Link to source info
     *
     * @return integer sourceId
     */
    public function registerSource($moduleName, $sourceName, $sourceClass, $sourceLink);

    public function unregisterSource($sourceId);
}