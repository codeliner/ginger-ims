<?php
namespace MockObject;

use Ginger\Model\Mapper\MapperLoaderInterface;
/**
 * Description of MapperLoader
 *
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 * @copyright (c) 2013, Alexander Miertsch
 */
class MapperLoader implements MapperLoaderInterface
{
    public function getMapper($mapperId)
    {
        if ($mapperId == 1) {
            return new Mapper(1, "testmapper", "/testmapper", "MockObject");
        }
    }

    public function registerMapper($moduleName, $mapperName, $mapperClass, $mapperLink)
    {
        //do nothing
    }

    public function unregisterMapper($mapperId)
    {

    }

    public function listMappers()
    {
        return array($this->getMapper(1));
    }
}