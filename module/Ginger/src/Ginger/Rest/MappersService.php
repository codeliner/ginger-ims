<?php
namespace Ginger\Rest;

use Cl\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;
use Ginger\Model\Configuration\ConnectorConfiguration;
use Ginger\Model\Mapper\MapperLoaderInterface;
use Ginger\Model\Mapper\AbstractMapper;

/**
 * Description of MappersService
 *
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 * @copyright (c) 2013, Alexander Miertsch
 */
class MappersService extends AbstractRestfulController
{
    /**
     *
     * @var MapperLoaderInterface
     */
    protected $mapperLoader;

    public function setMapperLoader(MapperLoaderInterface $mapperLoader)
    {
        $this->mapperLoader = $mapperLoader;
    }

    public function create($data)
    {

    }

    public function delete($id)
    {
        $this->mapperLoader->unregisterMapper($id);

        return new JsonModel(array('success' => true));
    }

    public function get($id)
    {
        $mapper = $this->mapperLoader->getMapper($id);

        if (!$mapper) {
            return $this->getResponse()->setStatusCode(404);
        }

        return new JsonModel($this->mapperToArray($mapper));
    }

    public function getList()
    {
        $mappers = $this->mapperLoader->listMappers();
        $mappersData = array();
        foreach ($mappers as $mapper) {
            $mappersData[] = $this->mapperToArray($mapper);
        }

        return new JsonModel($mappersData);
    }

    public function update($id, $data)
    {
        return $this->getResponse()->setStatusCode(405);
    }

    protected function mapperToArray(AbstractMapper $mapper)
    {
        $data = ConnectorConfiguration::elementToArray($mapper);

        //$data['class'] = str_replace('\\', '.', $data['class']);

        return $data;
    }
}