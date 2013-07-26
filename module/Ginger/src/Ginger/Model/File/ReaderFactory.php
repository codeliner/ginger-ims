<?php
namespace Ginger\Model\File;
/**
 * Description of ReaderFactory
 *
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 * @copyright (c) 2013, Alexander Miertsch
 */
class ReaderFactory
{
    /**
     * Plugin manager for loading readers
     *
     * @var null|ReaderPluginManager
     */
    public static $readers = null;

    /**
     * Registered config file extensions.
     * key is extension, value is reader instance or plugin name
     *
     * @var array
     */
    protected static $extensions = array(
        'csv'  => 'csv',
        'json' => 'json',
        'xml'  => 'xml',
    );


    /**
     * Read a data array from a file.
     *
     * @param  string  $filename
     *
     * @return array|Config
     *
     * @throws Exception\InvalidArgumentException
     * @throws Exception\RuntimeException
     */
    public static function fromFile($filename)
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
            if (!is_file($filename) || !is_readable($filename)) {
                throw new Exception\RuntimeException(sprintf(
                    "File '%s' doesn't exist or not readable",
                    $filename
                ));
            }

            $data = include $filename;
        } elseif (isset(static::$extensions[$extension])) {
            $reader = static::$extensions[$extension];
            if (!$reader instanceof Reader\ReaderInterface) {
                $reader = static::getReaderPluginManager()->get($reader);
                static::$extensions[$extension] = $reader;
            }

            /** @var Reader\ReaderInterface $reader  */
            $data = $reader->fromFile($filename);
        } else {
            throw new Exception\RuntimeException(sprintf(
                'Unsupported config file extension: .%s',
                $pathinfo['extension']
            ));
        }

        return $data;
    }

    /**
     * Set reader plugin manager
     *
     * @param ReaderPluginManager $readers
     */
    public static function setReaderPluginManager(ReaderPluginManager $readers)
    {
        static::$readers = $readers;
    }

    /**
     * Get the reader plugin manager
     *
     * @return ReaderPluginManager
     */
    public static function getReaderPluginManager()
    {
        if (static::$readers === null) {
            static::$readers = new ReaderPluginManager();
        }
        return static::$readers;
    }

    /**
     * Set config reader for file extension
     *
     * @param  string $extension
     * @param  string|Reader\ReaderInterface $reader
     * @throws Exception\InvalidArgumentException
     */
    public static function registerReader($extension, $reader)
    {
        $extension = strtolower($extension);

        if (!is_string($reader) && !$reader instanceof Reader\ReaderInterface) {
            throw new Exception\InvalidArgumentException(sprintf(
                'Reader should be plugin name, class name or ' .
                'instance of %s\Reader\ReaderInterface; received "%s"',
                __NAMESPACE__,
                (is_object($reader) ? get_class($reader) : gettype($reader))
            ));
        }

        static::$extensions[$extension] = $reader;
    }
}