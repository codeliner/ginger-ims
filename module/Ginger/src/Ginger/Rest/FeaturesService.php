<?php
namespace Ginger\Rest;

use Cl\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;
use Ginger\Model\Configuration\ConnectorConfiguration;
use Ginger\Model\Feature\FeatureLoaderInterface;
use Ginger\Model\Feature\AbstractFeature;
/**
 * Description of FeaturesService
 *
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 * @copyright (c) 2013, Alexander Miertsch
 */
class FeaturesService extends AbstractRestfulController
{
    /**
     *
     * @var FeatureLoaderInterface
     */
    protected $featureLoader;

    public function setFeatureLoader(FeatureLoaderInterface $featureLoader)
    {
        $this->featureLoader = $featureLoader;
    }

    public function create($data)
    {

    }

    public function delete($id)
    {

    }

    public function get($id)
    {
        $feature = $this->featureLoader->getFeature($id);

        if (!$feature) {
            return $this->getResponse()->setStatusCode(404)->setContent('Feature can not be found');
        }

        return new JsonModel($this->featureToArray($feature));
    }

    public function getList()
    {
        $features = $this->featureLoader->listFeatures();
        $featuresData = array();
        foreach ($features as $feature) {
            $featuresData[] = $this->featureToArray($feature);
        }

        return new JsonModel($featuresData);
    }

    public function update($id, $data)
    {

    }

    protected function featureToArray(AbstractFeature $feature)
    {
        $data = ConnectorConfiguration::elementToArray($feature);

        $data['class'] = str_replace('\\', '.', $data['class']);
        $data['type'] = $feature->getType();

        return $data;
    }
}