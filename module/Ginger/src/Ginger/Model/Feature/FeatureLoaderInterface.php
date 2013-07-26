<?php
namespace Ginger\Model\Feature;
/**
 * Description of FeatureLoaderInterface
 *
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 * @copyright (c) 2013, Alexander Miertsch
 */
interface FeatureLoaderInterface
{
    /**
     * Get feature info
     *
     * If a feature implements \Zend\ServiceManager\ServiceLocatorAwareInterface,
     * the loader should provide the serviceLocator to it
     *
     * @param integer $featureId
     *
     * @return AbstractFeature
     */
    public function getFeature($featureId);

    /**
     * @return AbstractFeature[] List of all registered features
     */
    public function listFeatures();

    /**
     * Register a feature in the system
     *
     * @param string $moduleName   Name of the module which registers the element
     * @param string $featureName  Name of the feature
     * @param string $featureClass Class of the feature or service manager alias
     * @param string $featureLink  Link to feature informations
     *
     * @return integer featureId
     */
    public function registerFeature($moduleName, $featureName, $featureClass, $featureLink);

    public function unregisterFeature($featureId);
}