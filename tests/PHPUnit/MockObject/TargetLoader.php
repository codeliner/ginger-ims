<?php
namespace MockObject;

use Ginger\Model\Target\TargetLoaderInterface;
/**
 * Description of TargetLoader
 *
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 * @copyright (c) 2013, Alexander Miertsch
 */
class TargetLoader implements TargetLoaderInterface
{
    public function getTarget($targetId)
    {
        if ($targetId == 1) {
            return new Target(1, "testtarget", "/testtarget", "MockObject");
        }
    }

    public function registerTarget($moduleName, $targetName, $targetClass, $targetLink)
    {
        //do nothing
    }

    public function unregisterTarget($targetId)
    {

    }

    public function listTargets()
    {
        return array($this->getTarget(1));
    }
}