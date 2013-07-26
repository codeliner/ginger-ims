<?php
namespace Ginger\Model\File;

use Zend\Config\Writer\PhpArray;
/**
 * Description of WriterFactory
 *
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 * @copyright (c) 2013, Alexander Miertsch
 */
class WriterFactory
{
    /**
     * Plugin manager for loading writers
     *
     * @var null|WriterPluginManager
     */
    public static $writers = null;

    /**
     * Registered file extensions.
     * key is extension, value is writer instance or plugin name
     *
     * @var array
     */
    protected static $extensions = array(
        'csv'  => 'csv',
        'json' => 'json',
        'xml'  => 'xml',
    );


    /**
     * Write data to a file.
     *
     * @param  string  $filename
     * @param  array   $data
     * @throws Exception\InvalidArgumentException
     * @throws Exception\RuntimeException
     */
    public static function toFile($filename, array $data)
    {
        $writer = static::getWriter($filename);

        /** @var Writer\WriterInterface $writer */
        $writer->toFile($filename, $data, true);
    }

    public static function addToFile($filename, array $entry)
    {
        $writer = static::getWriter($filename);

        $writer->addToFile($filename, $entry);
    }

    public static function getWriter($filename)
    {
        $pathinfo = pathinfo($filename);

        if (!isset($pathinfo['extension'])) {
            throw new Exception\RuntimeException(sprintf(
                'Filename "%s" is missing an extension and cannot be auto-detected',
                $filename
            ));
        }

        $extension = strtolower($pathinfo['extension']);

        if ($extension === 'php') {
            $writer = new PhpArray();
        } elseif (isset(static::$extensions[$extension])) {
            $writer = static::$extensions[$extension];
            if (!$writer instanceof Writer\WriterInterface) {
                $writer = static::getWriterPluginManager()->get($writer);
                static::$extensions[$extension] = $writer;
            }
        } else {
            throw new Exception\RuntimeException(sprintf(
                'Unsupported file extension: .%s',
                $pathinfo['extension']
            ));
        }

        return $writer;
    }

    /**
     * Set writer plugin manager
     *
     * @param ReaderPluginManager $readers
     */
    public static function setWriterPluginManager(WriterPluginManager $writers)
    {
        static::$writers = $writers;
    }

    /**
     * Get the writer plugin manager
     *
     * @return WriterPluginManager
     */
    public static function getWriterPluginManager()
    {
        if (static::$writers === null) {
            static::$writers = new WriterPluginManager();
        }
        return static::$writers;
    }

    /**
     * Set writer for file extension
     *
     * @param  string $extension
     * @param  string|WriterInterface $writer
     * @throws Exception\InvalidArgumentException
     */
    public static function registerWriter($extension, $writer)
    {
        $extension = strtolower($extension);

        if (!is_string($writer) && !$writer instanceof Writer\WriterInterface) {
            throw new Exception\InvalidArgumentException(sprintf(
                'Writer should be plugin name, class name or ' .
                'instance of Ginger\Model\File\Writer\WriterInterface; received "%s"',
                __NAMESPACE__,
                (is_object($writer) ? get_class($writer) : gettype($writer))
            ));
        }

        static::$extensions[$extension] = $writer;
    }
}
