<?php
namespace MockObject;

use Ginger\Model\Feature\FeatureLoaderInterface;
/**
 * Description of FeatureLoader
 *
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 * @copyright (c) 2013, Alexander Miertsch
 */
class FeatureLoader implements FeatureLoaderInterface
{
    public function getFeature($featureId)
    {
        if ($featureId == 1) {
            $feature = new ValueChangeFeature(1, 'ValueChangeFeature', '/value-change-feature', 'MockObject');
            $feature->setOptions(array(
                'attributes_to_alter' => array(
                    'name',
                    'address::city'
                )
            ));

            return $feature;
        }
    }

    public function listFeatures()
    {
        return array($this->getFeature(1));
    }

    public function registerFeature($moduleName, $featureName, $featureClass, $featureLink)
    {

    }

    public function unregisterFeature($featureId)
    {

    }
}