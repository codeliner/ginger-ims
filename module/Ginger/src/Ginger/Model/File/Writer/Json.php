<?php
namespace Ginger\Model\File\Writer;

use Ginger\Model\File\Reader\Json as JsonReader;
use Zend\Json\Json as JsonFormat;
/**
 * Description of Json
 *
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 * @copyright (c) 2013, Alexander Miertsch
 */
class Json extends AbstractWriter
{
    /**
     *
     * @var JsonReader
     */
    protected $jsonReader;

    public function addToFile($filename, $entry)
    {
        $reader = $this->getReader();
        $data = $reader->fromFile($filename);
        $data[] = $entry;
        $this->toFile($filename, $data);
    }

    protected function processData(array $data)
    {
        return JsonFormat::encode($data);
    }

    protected function getReader()
    {
        if (is_null($this->jsonReader)) {
            $this->jsonReader = new JsonReader();
        }

        return $this->jsonReader;
    }
}