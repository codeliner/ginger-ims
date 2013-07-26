<?php
namespace Ginger\Model\File\Reader;

use Ginger\Model\File\Exception;
use Zend\Json\Json as JsonFormat;
/**
 * Description of Json
 *
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 * @copyright (c) 2013, Alexander Miertsch
 */
class Json implements ReaderInterface
{

    public function fromFile($filename)
    {
        if (!is_file($filename) || !is_readable($filename)) {
            throw new Exception\RuntimeException(sprintf(
                "File '%s' doesn't exist or is not readable",
                $filename
            ));
        }

        return JsonFormat::decode(file_get_contents($filename), JsonFormat::TYPE_ARRAY);
    }
}