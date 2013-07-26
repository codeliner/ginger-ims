<?php
namespace Ginger\Model\File\Writer;

use Ginger\Model\File\Exception;
/**
 * Description of AbstractWriter
 *
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 * @copyright (c) 2013, Alexander Miertsch
 */
abstract class AbstractWriter implements WriterInterface
{
     /**
     * toFile(): defined by Writer interface.
     *
     * @see    WriterInterface::toFile()
     * @param  string  $filename
     * @param  array   $data
     * @param  boolean $exclusiveLock
     * @return void
     * @throws Exception\InvalidArgumentException
     * @throws Exception\RuntimeException
     */
    public function toFile($filename, array $data, $exclusiveLock = true)
    {
        if (empty($filename)) {
            throw new Exception\InvalidArgumentException('No file name specified');
        }

        $flags = 0;
        if ($exclusiveLock) {
            $flags |= LOCK_EX;
        }

        set_error_handler(
            function($error, $message = '', $file = '', $line = 0) use ($filename) {
                throw new Exception\RuntimeException(sprintf(
                    'Error writing to "%s": %s',
                    $filename, $message
                ), $error);
            }, E_WARNING
        );
        file_put_contents($filename, $this->processData($data), $flags);
        restore_error_handler();
    }

    /**
     * @param array $config
     * @return string
     */
    abstract protected function processData(array $data);
}