<?php
namespace Ginger\Rest;

use Cl\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;
use Ginger\Model\Configuration\ConnectorConfiguration;
use Ginger\Model\Target\TargetLoaderInterface;
use Ginger\Model\Target\AbstractTarget;

/**
 * Description of TargetsService
 *
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 * @copyright (c) 2013, Alexander Miertsch
 */
class TargetsService extends AbstractRestfulController
{
    /**
     *
     * @var TargetLoaderInterface
     */
    protected $targetLoader;

    public function setTargetLoader(TargetLoaderInterface $targetLoader)
    {
        $this->targetLoader = $targetLoader;
    }

    public function create($data)
    {

    }

    public function delete($id)
    {
        $this->targetLoader->unregisterTarget($id);

        return new JsonModel(array('success' => true));
    }

    public function get($id)
    {
        $target = $this->targetLoader->getTarget($id);

        if (!$target) {
            return $this->getResponse()->setStatusCode(404);
        }

        return new JsonModel($this->targetToArray($target));
    }

    public function getList()
    {
        $targets = $this->targetLoader->listTargets();
        $targetsData = array();
        foreach ($targets as $target) {
            $targetsData[] = $this->targetToArray($target);
        }

        return new JsonModel($targetsData);
    }

    public function update($id, $data)
    {
        return $this->getResponse()->setStatusCode(405);
    }

    protected function targetToArray(AbstractTarget $target)
    {
        $data = ConnectorConfiguration::elementToArray($target);

        //$data['class'] = str_replace('\\', '.', $data['class']);
        $data['data_type'] = $target->getDataType();

        return $data;
    }
}