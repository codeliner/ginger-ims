<?php
namespace Ginger\Model\File;

use Zend\Stdlib\ArrayUtils;
/**
 * Description of File
 *
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 * @copyright (c) 2013, Alexander Miertsch
 */
class File
{
    protected $dir;
    protected $filename;

    public function __construct($dir, $filename)
    {
        $this->dir = $dir;
        $this->filename = $filename;
    }

    public function getFilename()
    {
        return $this->filename;
    }

    public function getDir()
    {
        return $this->dir;
    }

    public function getPath()
    {
        return $this->dir . '/' . $this->filename;
    }

    /**
     *
     * @return array
     *
     * @throws Exception\RuntimeException
     */
    public function getData()
    {
        try {
            return ReaderFactory::fromFile($this->getPath());
        } catch (\Zend\Config\Exception\ExceptionInterface $e) {
            throw new Exception\RuntimeException(
                sprintf(
                    'An error occured while reading data from file "%s". See previous exception for more details.',
                    $this->getPath()
                    ),
                $e->getCode(),
                $e
                );
        }

    }

    public function writeData(array $data)
    {
        WriterFactory::toFile($this->getPath(), $data);
        return $this;
    }

    public function appendElement(array $dataElement)
    {
        WriterFactory::addToFile($this->getPath(), $dataElement);
    }

    public function mergeData(array $data)
    {
        $data = ArrayUtils::merge($this->getData(), $data);

        WriterFactory::toFile($this->getPath(), $data);
    }

    /**
     * Rename the file
     *
     * @param stirng $filename
     *
     * @return File
     *
     * @throws Exception\RuntimeException
     */
    public function rename($filename)
    {
        set_error_handler(
            function($error, $message = '', $file = '', $line = 0) use ($filename) {
                throw new Exception\RuntimeException(sprintf(
                    'Error renaming file "%s" to "%s": %s',
                    $filename, $message
                ), $error);
            }, E_WARNING
        );

        rename($this->dir . '/' . $this->filename, $this->dir . '/' . $filename);

        restore_error_handler();

        $this->filename = $filename;
        return $this;
    }

    /**
     * @param string $dir
     *
     * @return File
     *
     * @throws Exception\RuntimeException
     */
    public function move($dir)
    {
        $oldDir = $this->dir;
        $filename = $this->filename;

        set_error_handler(
            function($error, $message = '', $file = '', $line = 0) use ($dir, $oldDir, $filename) {
                throw new Exception\RuntimeException(sprintf(
                    'Failed to move file "%s" from "%s" to "%s": %s',
                    $filename, $oldDir, $dir, $message
                ), $error);
            }, E_WARNING
        );

        copy($this->getPath(), $dir . '/' . $this->filename);

        restore_error_handler();

        $this->remove();


        $this->dir = $dir;
        return $this;
    }

    /**
     * @param string $dir
     *
     * @return File
     *
     * @throws Exception\RuntimeException
     */
    public function copy($dir)
    {
        $oldDir = $this->dir;
        $filename = $this->filename;

        set_error_handler(
            function($error, $message = '', $file = '', $line = 0) use ($dir, $oldDir, $filename) {
                throw new Exception\RuntimeException(sprintf(
                    'Failed to copy file "%s" from "%s" to "%s": %s',
                    $filename, $oldDir, $dir, $message
                ), $error);
            }, E_WARNING
        );

        copy($this->getPath(), $dir . '/' . $this->filename);

        restore_error_handler();

        return new File($dir, $this->filename);
    }

    /**
     *
     * @return File
     *
     * @throws Exception\RuntimeException
     */
    public function remove()
    {
        $dir = $this->dir;
        $filename = $this->filename;

        set_error_handler(
            function($error, $message = '', $file = '', $line = 0) use ($dir, $filename) {
                throw new Exception\RuntimeException(sprintf(
                    'Failed to remove file "%s" from "%s": %s',
                    $filename, $dir, $message
                ), $error);
            }, E_WARNING
        );

        unlink($this->getPath());

        restore_error_handler();

        return $this;
    }
}