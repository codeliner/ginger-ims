<?php
namespace Ginger\Model\Feature;

use Zend\Validator\ValidatorPluginManager;
use Zend\Validator\ValidatorInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Ginger\Model\Connector\ConnectorEvent;

use Ginger\Job\Run\Message;
/**
 * Description of ValidatorFeature
 *
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 * @copyright (c) 2013, Alexander Miertsch
 */
class ValidatorFeature extends AbstractFeature implements ServiceLocatorAwareInterface
{
    const ERROR_HANDLING_BREAK = "break";
    const ERROR_HANDLING_SKIP = "skip";
    const ERROR_HANDLING_WARN = "warn";

    const NAME_PREFIX = "Validator::";
    /**
     *
     * @var ServiceLocatorInterface
     */
    protected $serviceLocator;

    protected $message;

    protected $errorHandling = 'break';

    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }

    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
    }

    public function alterValue($value, $attributeToAlter, ConnectorEvent $e)
    {
        $val = $this->getValidator();
        $translator = $this->serviceLocator->get('translator');

        if (!$val->isValid($value)) {

            switch ($this->errorHandling) {
                case static::ERROR_HANDLING_BREAK:
                    $message = new Message(Message::TYPE_ERROR);
                    $e->stopPropagation();
                    break;
                case static::ERROR_HANDLING_SKIP:
                    $message = new Message(Message::TYPE_WARNING);
                    $e->skipItem();
                    break;

                case static::ERROR_HANDLING_WARN:
                    $message = new Message(Message::TYPE_WARNING);
                    break;
            }

            $msgText = sprintf($translator->translate('ERROR::ATTRIBUTE_VALIDATION_FAILED'), $attributeToAlter, $value) . "\n";

            foreach ($val->getMessages() as $msg) {
                $msgText.= $msg . "\n";
            }

            $message->setText($msgText);
            $this->message = $message;
        }

        return $value;
    }

    public function getType()
    {
        return static::TYPE_VALIDATOR;
    }

    public function getName()
    {
        return static::NAME_PREFIX . $this->name;
    }

    public function getMessage()
    {
        $message = $this->message;
        $this->message = null;
        return $message;
    }

    /**
     *
     * @return ValidatorInterface
     */
    protected function getValidator()
    {
        return $this->getServiceLocator()->get('ValidatorPluginManager')->get($this->name);
    }

    protected function getAdvancedOptions()
    {
        return array('error_handling' => $this->errorHandling);
    }

    protected function setAdvancedOptions(array $options)
    {
        if (isset($options['error_handling'])) {
            $this->errorHandling = $options['error_handling'];
        }
    }
}