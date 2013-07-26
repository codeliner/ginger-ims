<?php
namespace Ginger\Model\Script;

use Ginger\Model\Source\AbstractSource;
use Ginger\Model\File\Exception\FileNotFoundException;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
/**
 * Description of SourceScript
 *
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 * @copyright (c) 2013, Alexander Miertsch
 */
class SourceScript extends AbstractSource implements ServiceLocatorAwareInterface
{
    /**
     *
     * @var ServiceLocatorInterface
     */
    protected $serviceLocator;

    protected $scriptName = "script.php";

    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
    }

    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }

    public function getData()
    {
        if (!file_exists('scripts/' . $this->scriptName)) {
            throw new FileNotFoundException(
                sprintf(
                    'Script "%s" can not be found in "scripts" directory.',
                    $this->scriptName
                    )
                );
        }

        $response = include 'scripts/' . $this->scriptName;

        if (!$response instanceof \Traversable) {
            $response = array(array('response' => $response));
        }

        return $response;
    }

    public function getDataStructure()
    {
        return null;
    }

    public function getDataType()
    {
        return static::DATA_TYPE_NOT_DEFINED;
    }

    public function getItemCount()
    {
        return 0;
    }

    public function getOptions()
    {
        return array(
            'script_name' => $this->scriptName
        );
    }

    public function setOptions(array $options)
    {
        $this->scriptName = $options['script_name'];
    }
}