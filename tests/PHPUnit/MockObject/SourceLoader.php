<?php
namespace MockObject;

use Ginger\Model\Source\SourceLoaderInterface;
/**
 * Description of SourceLoader
 *
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 * @copyright (c) 2013, Alexander Miertsch
 */
class SourceLoader implements SourceLoaderInterface
{
    protected $registeredSources = array();

    public function getSource($sourceId)
    {
        if ($sourceId == 1) {
            return new Source(1, "testsource", "/testsource", "MockObject");
        }
    }

    public function registerSource($moduleName, $sourceName, $sourceClass, $sourceLink)
    {
        $this->registeredSources[] = array(
            'module' => $moduleName,
            'name' => $sourceName,
            'class' => $sourceClass,
            'link' => $sourceLink
        );
    }

    public function getSources()
    {
        return $this->registeredSources;
    }

    public function unregisterSource($sourceId)
    {

    }

    public function listSources()
    {
        return array($this->getSource(1));
    }
}