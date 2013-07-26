<?php
namespace Ginger\Job\Run;
/**
 * Description of Message
 *
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 * @copyright (c) 2013, Alexander Miertsch
 */
class Message
{
    const TYPE_INFO = "info";
    const TYPE_WARNING = "warning";
    const TYPE_ERROR = "error";

    private $timestamp;

    private $text = "";

    private $type = "info";

    public function __construct($type = "info", $text = "")
    {
        $this->timestamp = new \DateTime();
        $this->type = $type;
        $this->text = $text;
    }

    public function getTimestamp()
    {
        return $this->timestamp;
    }

    public function setTimestamp($timestamp)
    {
        $this->timestamp = $timestamp;
    }

    public function getText()
    {
        return $this->text;
    }

    public function setText($text)
    {
        $this->text = $text;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setType($type)
    {
        $this->type = $type;
    }

    public function getArrayCopy()
    {
        return array(
            'type' => $this->getType(),
            'text' => $this->getText(),
            'timestamp' => $this->getTimestamp()->format('Y-m-d H:i:s'),
        );
    }
}