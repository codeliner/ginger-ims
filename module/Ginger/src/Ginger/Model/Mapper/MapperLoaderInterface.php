<?php
namespace Ginger\Model\Mapper;
/**
 * Description of MapperLoaderInterface
 *
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 * @copyright (c) 2013, Alexander Miertsch
 */
interface MapperLoaderInterface
{
    /**
     * Get mapper info
     *
     * If a mapper implements \Zend\ServiceManager\ServiceLocatorAwareInterface,
     * the loader should provide the serviceLocator to it
     *
     * @param integer $mapperId
     *
     * @return AbstractMapper
     */
    public function getMapper($mapperId);

    /**
     * @return AbstractMapper[] List of all registered mappers
     */
    public function listMappers();

    /**
     * Register a Mapper in the system
     *
     * @param string $moduleName  Name of the module which registers the element
     * @param string $mapperName  Name of the mapper
     * @param string $mapperClass Class of the mapper or service manager alias
     * @param string $mapperLink  Link to mapper informations
     *
     * @return integer MapperId
     */
    public function registerMapper($moduleName, $mapperName, $mapperClass, $mapperLink);

    public function unregisterMapper($mapperId);
}