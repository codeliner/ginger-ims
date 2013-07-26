<?php
namespace Ginger\Job\Run;

use Ginger\Model\Configuration\ConnectorConfiguration;
/**
 * Description of ConfigurationRun
 *
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 * @copyright (c) 2013, Alexander Miertsch
 */
class ConfigurationRun
{
    private $id;

    private $startTime;

    private $endTime;

    private $messages;

    private $success = false;

    private $configurationId;

    private $totalItemCount = 0;

    private $insertedItemCount = 0;

    /**
     * @var ConnectorConfiguration
     */
    private $configuration;

    public function __construct($id)
    {
        $this->id = $id;
        $this->startTime = new \DateTime();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getStartTime()
    {
        return $this->startTime;
    }

    public function setStartTime($startTime)
    {
        $this->startTime = $startTime;
    }

    public function getEndTime()
    {
        return $this->endTime;
    }

    public function setEndTime($endTime)
    {
        $this->endTime = $endTime;
    }

    public function getMessages()
    {
        return $this->messages;
    }

    public function setMessages($messages)
    {
        $this->messages = $messages;
    }

    public function addMessage(Message $message)
    {
        $this->messages[] = $message;
    }

    public function getSuccess()
    {
        return $this->success;
    }

    public function setSuccess($success)
    {
        $this->success = $success;
    }

    public function getConfigurationId()
    {
        return $this->configurationId;
    }

    public function setConfigurationId($configId)
    {
        $this->configurationId = $configId;
    }

    public function getTotalItemCount()
    {
        return $this->totalItemCount;
    }

    public function setTotalItemCount($totalItemCount)
    {
        $this->totalItemCount = $totalItemCount;
    }

    public function getInsertedItemCount()
    {
        return $this->insertedItems;
    }

    public function setInsertedItemCount($insertedItems)
    {
        $this->insertedItems = $insertedItems;
    }

    public function getArrayCopy()
    {
        $data = array(
            'id' => $this->getId(),
            'configurationId' => $this->getConfigurationId(),
            'totalItemCount' => $this->getTotalItemCount(),
            'insertedItemCount' => $this->getInsertedItemCount(),
            'success' => $this->getSuccess(),
        );

        if (is_null($this->getStartTime())) {
            $data['startTime'] = null;
        } else {
            $data['startTime'] = $this->getStartTime()->format('Y-m-d H:i:s');
        }

        if (is_null($this->getEndTime())) {
            $data['endTime'] = null;
        } else {
            $data['endTime'] = $this->getEndTime()->format('Y-m-d H:i:s');
        }

        $messages = array();
        foreach($this->messages as $message) {
            $messages[] = $message->getArrayCopy();
        }

        $data['messages'] = $messages;

        return $data;
    }
}