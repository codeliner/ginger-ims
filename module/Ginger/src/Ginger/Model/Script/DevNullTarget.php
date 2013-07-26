<?php
namespace Ginger\Model\Script;

use Ginger\Model\Target\AbstractTarget;
use Ginger\Model\Connector\ConnectorEvent;
use Zend\EventManager\EventManagerInterface;
/**
 * Description of DevNullTarget
 *
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 * @copyright (c) 2013, Alexander Miertsch
 */
class DevNullTarget extends AbstractTarget
{
    protected $logFile;
    protected $logFileName = 'dev-null.log';

    public function addItem($item)
    {
        fwrite($this->getLogFile(), print_r($item, true) . "\r\n\r\n");
    }

    public function attach(EventManagerInterface $events)
    {
        $this->listeners = $events->attach(ConnectorEvent::EVENT_FINISH_INSERT, array($this, 'onFinishInsert'));
    }

    public function onFinishInsert(ConnectorEvent $e)
    {
        fclose($this->getLogFile());
    }

    public function getDataStructure()
    {
        return null;
    }

    public function getDataType()
    {
        return static::DATA_TYPE_NOT_DEFINED;
    }

    public function getOptions()
    {
        return array(
            'log_file_name' => $this->logFileName,
        );
    }

    public function setOptions(array $options)
    {
        $this->logFileName = $options['log_file_name'];
    }

    protected function getLogFile()
    {
        if (is_null($this->logFile)) {
            $this->logFile = fopen('data/logs/' . $this->logFileName, 'w+');
        }

        return $this->logFile;
    }
}