<?php
namespace MockObject;

use Ginger\Model\Mapper\AbstractMapper;
use Ginger\Model\Connector\ConnectorEvent;
/**
 * Description of Mapper
 *
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 * @copyright (c) 2013, Alexander Miertsch
 */
class Mapper extends AbstractMapper
{
    protected $options = array();

    public function mapItem($item, ConnectorEvent $e)
    {
        return $item .= "Mapped";
    }

    public function getOptions()
    {
        return $this->options;
    }

    public function setOptions(array $options)
    {
        $this->options = $options;
    }
}