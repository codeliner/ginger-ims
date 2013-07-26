<?php
namespace Ginger\Model\Configuration;

use Ginger\Model\Connector\AbstractElement;
use Ginger\Model\Source\AbstractSource;
use Ginger\Model\Mapper\AbstractMapper;
use Ginger\Model\Target\AbstractTarget;
use Ginger\Model\Feature\AbstractFeature;
/**
 * Description of ConnectorConfiguration
 *
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 * @copyright (c) 2013, Alexander Miertsch
 */
class ConnectorConfiguration
{
    protected $id;
    protected $source;
    protected $mapper;
    protected $target;

    protected $sourceLoader;
    protected $targetLoader;
    protected $mapperLoader;
    protected $featureLoader;

    protected $features = array();

    public static function elementToArray(AbstractElement $element)
    {
        $data = array(
            'id' => $element->getId(),
            'name' => $element->getName(),
            'class' => get_class($element),
            'link' => $element->getLink(),
            'module' => $element->getModule(),
            'options' => $element->getOptions()
        );

        if ($element instanceof AbstractSource) {
            $data['itemName'] = $element->getItemName();
        } else if ($element instanceof AbstractTarget) {
            $data['action'] = $element->getAction();
        }

        return $data;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     *
     * @return AbstractSource
     */
    public function getSource()
    {
        return $this->source;
    }

    public function setSource(AbstractSource $source)
    {
        $this->source = $source;
    }

    /**
     *
     * @return AbstractMapper
     */
    public function getMapper()
    {
        return $this->mapper;
    }

    public function setMapper(AbstractMapper $mapper)
    {
        $this->mapper = $mapper;
    }

    /**
     *
     * @return AbstractTarget
     */
    public function getTarget()
    {
        return $this->target;
    }

    public function setTarget(AbstractTarget $target)
    {
        $this->target = $target;
    }

    /**
     *
     * @return AbstractFeature[]
     */
    public function getFeatures()
    {
        return $this->features;
    }

    public function setFeatures(array $features)
    {
        $this->features = $features;
    }

    public function addFeature(AbstractFeature $feature)
    {
        $this->features[] = $feature;
    }

    public function removeFeature(AbstractFeature $feature)
    {
        $newFeatures = array();

        foreach ($this->features as $oldFeature) {
            if ($oldFeature != $feature) {
                $newFeatures[] = $oldFeature;
            }
        }

        $this->features = $newFeatures;
    }

    public function getSourceLoader()
    {
        return $this->sourceLoader;
    }

    public function setSourceLoader($sourceLoader)
    {
        $this->sourceLoader = $sourceLoader;
    }

    public function getTargetLoader()
    {
        return $this->targetLoader;
    }

    public function setTargetLoader($targetLoader)
    {
        $this->targetLoader = $targetLoader;
    }

    public function getMapperLoader()
    {
        return $this->mapperLoader;
    }

    public function setMapperLoader($mapperLoader)
    {
        $this->mapperLoader = $mapperLoader;
    }

    public function setFeatureLoader($featureLoader)
    {
        $this->featureLoader = $featureLoader;
    }

    public function getFeatureLoader()
    {
        return $this->featureLoader;
    }

    public function getArrayCopy()
    {
        $featuresData = array();

        foreach($this->features as $feature) {
            $featuresData[] = static::elementToArray($feature);
        }

        $copy = array(
            'id' => $this->getId(),
            'source' => static::elementToArray($this->getSource()),
            'target' => static::elementToArray($this->getTarget()),
            'features' => $featuresData
        );

        $mapper = $this->getMapper();

        if ($mapper) {
            $copy['mapper'] = static::elementToArray($mapper);
        }

        return $copy;
    }

    public function serialize()
    {
        $config = array();

        if (!is_null($this->source)) {
            $config['source'] = array(
                'id' => $this->source->getId(),
                'options' => $this->source->getOptions(),
            );
        }

        if (!is_null($this->target)) {
            $config['target'] = array(
                'id' => $this->target->getId(),
                'options' => $this->target->getOptions(),
            );
        }

        if (!is_null($this->mapper)) {
            $config['mapper'] = array(
                'id' => $this->mapper->getId(),
                'options' => $this->mapper->getOptions(),
            );
        }

        $config['features'] = array();

        foreach($this->features as $feature) {
            $config['features'][] = array(
                'id' => $feature->getId(),
                'options' => $feature->getOptions()
            );
        }

        return json_encode($config);
    }

    public function unserialize($serialized)
    {
        $config = json_decode($serialized, true);
        foreach ($config as $itemType => $itemConfig) {
            if ($itemType == 'features') {
                foreach ($itemConfig as $featureConfig) {
                    $this->features[] = $this->resetItem('feature', $featureConfig);
                }
            } else {
                $this->resetItem($itemType, $itemConfig);
            }
        }
    }

    protected function resetItem($itemType, $itemConfig)
    {
        $itemId = $itemConfig['id'];

        $item = $this->{$itemType . 'Loader'}->{'get' . ucfirst($itemType)}($itemId);

        $item->setOptions($itemConfig['options']);
        if ($itemType == 'feature') {
            return $item;
        } else {
            $this->{'set' . ucfirst($itemType)}($item);
        }
    }
}