<?php
namespace Ginger\Model\File\Reader;

use XMLReader;
use Ginger\Model\File\Exception;
/**
 * Description of Xml
 *
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 * @copyright (c) 2013, Alexander Miertsch
 */
class Xml implements ReaderInterface
{
    /**
     * XML Reader instance.
     *
     * @var XMLReader
     */
    protected $reader;
    /**
     * Nodes to handle as plain text.
     *
     * @var array
     */
    protected $textNodes = array(
        XMLReader::TEXT,
        XMLReader::CDATA,
        XMLReader::WHITESPACE,
        XMLReader::SIGNIFICANT_WHITESPACE
    );

    public function fromFile($filename)
    {
        if (!is_file($filename) || !is_readable($filename)) {
            throw new Exception\RuntimeException(sprintf(
                "File '%s' doesn't exist or not readable",
                $filename
            ));
        }

        $this->reader = new XMLReader();
        $this->reader->open($filename, null, LIBXML_XINCLUDE);

        set_error_handler(
            function($error, $message = '', $file = '', $line = 0) use ($filename) {
                throw new Exception\RuntimeException(sprintf(
                    'Error reading XML file "%s": %s',
                    $filename, $message
                ), $error);
            }, E_WARNING
        );
        $return = $this->process();
        restore_error_handler();

        return $return;
    }

    /**
     * Process data from the created XMLReader.
     *
     * @return array
     */
    protected function process()
    {
        return $this->processNextElement('');
    }

    /**
     * Process the next inner element.
     *
     * @return mixed
     */
    protected function processNextElement($path)
    {
        $children = array();
        $text     = '';
        $pathCache = array();

        error_log('process path: ' . $path);

        while ($this->reader->read()) {
            if ($this->reader->nodeType === XMLReader::ELEMENT) {
                if ($this->reader->depth === 0) {
                    return $this->processNextElement($path);
                }

                $attributes = $this->getAttributes();
                $name       = $this->reader->name;
                $nextPath = !empty($path)? $path . '::' . $name : $name;

                error_log('next path: ' . $nextPath);

                if ($this->reader->isEmptyElement) {
                    $child = array();
                } else {
                    $child = $this->processNextElement($nextPath);
                }

                if ($attributes) {
                    if (!is_array($child)) {
                        $child = array();
                    }

                    $child = array_merge($child, $attributes);
                }

                if (array_key_exists($nextPath, $pathCache)) {

                    error_log('path exists: ' . $nextPath);

                    if (isset($children[$name])) {
                        error_log('children name exists: ' . $name);
                        $children = array($children[$name]);
                    }

                    $children[] = $child;


                } else {
                    $children[$name] = $child;
                }

                $pathCache[$nextPath] = 1;

            } elseif ($this->reader->nodeType === XMLReader::END_ELEMENT) {
                break;
            } elseif (in_array($this->reader->nodeType, $this->textNodes)) {
                $text .= $this->reader->value;
            }
        }

        return $children ?: $text;
    }

    /**
     * Get all attributes on the current node.
     *
     * @return array
     */
    protected function getAttributes()
    {
        $attributes = array();

        if ($this->reader->hasAttributes) {
            while ($this->reader->moveToNextAttribute()) {
                $attributes[$this->reader->localName] = $this->reader->value;
            }

            $this->reader->moveToElement();
        }

        return $attributes;
    }
}