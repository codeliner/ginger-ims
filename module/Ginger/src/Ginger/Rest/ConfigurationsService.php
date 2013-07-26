<?php
namespace Ginger\Rest;

use Cl\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;
use Ginger\Job\JobLoaderInterface;
use Ginger\Job\Job;
use Ginger\Model\Configuration\ConnectorConfiguration;
use Ginger\Model\Source;
use Ginger\Model\Target;
use Ginger\Model\Mapper;
use Ginger\Model\Feature;
use Zend\Mvc\MvcEvent;
/**
 * Description of ConfigurationsService
 *
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 * @copyright (c) 2013, Alexander Miertsch
 */
class ConfigurationsService extends AbstractRestfulController
{
    /**
     *
     * @var JobLoaderInterface
     */
    protected $jobLoader;
    /**
     *
     * @var Source\SourceLoaderInterface
     */
    protected $sourceLoader;

    /**
     *
     * @var Target\TargetLoaderInterface
     */
    protected $targetLoader;

    /**
     *
     * @var Mapper\MapperLoaderInterface
     */
    protected $mapperLoader;


    /**
     *
     * @var Feature\FeatureLoaderInterface
     */
    protected $featureLoader;

    /**
     *
     * @var Job
     */
    protected $job;

    public function setJobLoader($jobLoader)
    {
        $this->jobLoader = $jobLoader;
    }

    public function setSourceLoader($sourceLoader)
    {
        $this->sourceLoader = $sourceLoader;
    }

    public function setTargetLoader($targetLoader)
    {
        $this->targetLoader = $targetLoader;
    }

    public function setMapperLoader($mapperLoader)
    {
        $this->mapperLoader = $mapperLoader;
    }

    public function setFeatureLoader($featureLoader)
    {
        $this->featureLoader = $featureLoader;
    }

    /**
     * Register the default events for this controller
     *
     * @return void
     */
    protected function attachDefaultListeners()
    {
        parent::attachDefaultListeners();
        $events = $this->getEventManager();
        $events->attach(MvcEvent::EVENT_DISPATCH, array($this, 'checkJobname'), 100);
    }

    public function checkJobname(MvcEvent $e)
    {
        $jobname = $e->getRouteMatch()->getParam('jobname', '');

        if (!$this->jobLoader->hasJob($jobname)) {
            $this->getResponse()->setStatusCode(404);
            return $this->getResponse();
        }

        $this->job = $this->jobLoader->loadJob($jobname);
    }

    public function create($data)
    {
        $config = new ConnectorConfiguration();

        $source = $this->sourceLoader->getSource($data['source']['id']);
        $source->setOptions($data['source']['options']);
        $config->setSource($source);

        $target = $this->targetLoader->getTarget($data['target']['id']);
        $target->setOptions($data['target']['options']);
        $config->setTarget($target);

        if (isset($data['mapper'])) {
            $mapper = $this->mapperLoader->getMapper($data['mapper']['id']);
            $mapper->setOptions($data['mapper']['options']);
            $config->setMapper($mapper);
        }

        if (isset($data['features'])) {
            $featureCollection = array();
            foreach ($data['features'] as $featureData) {
                $feature = $this->featureLoader->getFeature($featureData['id']);
                $feature->setOptions($featureData['options']);
                $featureCollection[] = $feature;
            }
            $config->setFeatures($featureCollection);
        }

        $this->job->addConfiguration($config);

        $this->jobLoader->saveJob($this->job);

        return new JsonModel($config->getArrayCopy());
    }

    public function delete($id)
    {
        $configs = $this->job->getConfigurations();
        $newConfigs = array();
        $newConfigIds = array();
        foreach($configs as $config) {
            if ($config->getId() != $id) {
                $newConfigs[] = $config;
                $newConfigIds[] = $config->getId();
            }
        }

        $this->job->setConfigurations($newConfigs);
        $this->jobLoader->saveJob($this->job);

        return new JsonModel(array('success' => true));
    }

    public function get($id)
    {
        $configs = $this->job->getConfigurations();

        $configData = array();

        foreach($configs as $config) {
            if ($config->getId() == $id) {
                $configData[] = $config->getArrayCopy();
                break;
            }
        }

        if (empty($configData)) {
            return $this->getResponse()->setStatusCode(404)->setContent('Config can not be found');
        }

        return new JsonModel($configData);
    }

    public function getList()
    {
        $configs = $this->job->getConfigurations();

        $configsData = array();

        foreach($configs as $config) {
            $configsData[] = $config->getArrayCopy();
        }

        return new JsonModel($configsData);
    }

    public function update($id, $data)
    {
        $configs = $this->job->getConfigurations();
        $configFound = false;
        foreach($configs as $config) {
            if ($config->getId() == $id) {
                $configFound = true;
                $source = $this->sourceLoader->getSource($data['source']['id']);
                $source->setOptions($data['source']['options']);
                $config->setSource($source);

                $target = $this->targetLoader->getTarget($data['target']['id']);
                $target->setOptions($data['target']['options']);
                $config->setTarget($target);

                if (isset($data['mapper'])) {
                    $mapper = $this->mapperLoader->getMapper($data['mapper']['id']);
                    $mapper->setOptions($data['mapper']['options']);
                    $config->setMapper($mapper);
                }

                if (isset($data['features'])) {
                    $featureCollection = array();
                    foreach ($data['features'] as $featureData) {
                        $feature = $this->featureLoader->getFeature($featureData['id']);
                        $feature->setOptions($featureData['options']);
                        $featureCollection[] = $feature;
                    }
                    $config->setFeatures($featureCollection);
                }

                $this->jobLoader->saveJob($this->job);
                $configFound = $config;
                break;
            }
        }

        if (!$configFound) {
            return $this->getResponse()->setStatusCode(404);
        }

        return new JsonModel($configFound->getArrayCopy());
    }
}

