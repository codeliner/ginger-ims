<?php
namespace Ginger\Model\Directory;

use Ginger\Model\File\File;
/**
 * Description of DirectoryIterator
 *
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 * @copyright (c) 2013, Alexander Miertsch
 */
class DirectoryIterator implements \Iterator
{
    const MODE_DATA = "data";
    const MODE_FILE = "file";

    protected $dir;
    protected $filenames;
    protected $mode;
    protected $pointer = 0;

    public function __construct($dir, array $filenames, $mode = "file")
    {
        $this->dir = $dir;
        $this->filenames = $filenames;
        $this->mode = $mode;
    }

    public function current()
    {
        $file = new File($this->dir, $this->filenames[$this->pointer]);

        if ($this->mode == static::MODE_FILE) {
            return $file;
        } else if ($this->mode == static::MODE_DATA) {
            return $file->getData();
        } else {
            throw new Exception\InvalidArgumentException(
                sprintf(
                    'Invalid mode "%s" for "%s"',
                    $this->mode,
                    get_class($this)
                    )
                );
        }
    }

    public function key()
    {
        return $this->pointer;
    }

    public function next()
    {
        $this->pointer++;
    }

    public function rewind()
    {
        $this->pointer = 0;
    }

    public function valid()
    {
        return isset($this->filenames[$this->pointer]);
    }
}