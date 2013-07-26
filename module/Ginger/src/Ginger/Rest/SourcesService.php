<?php
namespace Ginger\Rest;

use Cl\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;
use Ginger\Model\Configuration\ConnectorConfiguration;
use Ginger\Model\Source\SourceLoaderInterface;
use Ginger\Model\Source\AbstractSource;

/**
 * Description of SourcesService
 *
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 * @copyright (c) 2013, Alexander Miertsch
 */
class SourcesService extends AbstractRestfulController
{
    /**
     *
     * @var SourceLoaderInterface
     */
    protected $sourceLoader;

    public function setSourceLoader(SourceLoaderInterface $sourceLoader)
    {
        $this->sourceLoader = $sourceLoader;
    }

    public function create($data)
    {
        $this->getResponse()->setStatusCode(405);
        return new JsonModel();
    }

    public function delete($id)
    {
        $this->sourceLoader->unregisterSource($id);

        return new JsonModel(array('success' => true));
    }

    public function get($id)
    {
        $source = $this->sourceLoader->getSource($id);

        if (!$source) {
            return $this->getResponse()->setStatusCode(404);
        }

        return new JsonModel($this->sourceToArray($source));
    }

    public function getList()
    {
        $sources = $this->sourceLoader->listSources();
        $sourcesData = array();
        foreach ($sources as $source) {
            $sourcesData[] = $this->sourceToArray($source);
        }

        return new JsonModel($sourcesData);
    }

    public function update($id, $data)
    {
        return $this->getResponse()->setStatusCode(405);
    }

    protected function sourceToArray(AbstractSource $source)
    {
        $data = ConnectorConfiguration::elementToArray($source);

        $data['data_type'] = $source->getDataType();

        return $data;
    }
}