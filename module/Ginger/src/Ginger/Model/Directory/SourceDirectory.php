<?php
namespace Ginger\Model\Directory;

use Ginger\Model\Source\AbstractSource;
use Ginger\Service\DataStructure\DocumentStructureReader;

/**
 * Description of SourceDirectory
 *
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 * @copyright (c) 2013, Alexander Miertsch
 */
class SourceDirectory extends AbstractSource
{
    protected $sourceDir = 'inbox';
    protected $filePattern = '/^.+\..+$/';
    protected $iteratorMode = 'file';

    protected $directoryIterator;
    protected $filenames;

    public function getItemName()
    {
        if ($this->iteratorMode == 'file') {
            return 'file';
        } else {
            return parent::getItemName();
        }
    }

    public function getData()
    {
        if (is_null($this->directoryIterator)) {
            $this->directoryIterator = new DirectoryIterator($this->getDir(), $this->getFilenames(), $this->iteratorMode);
        }

        return $this->directoryIterator;
    }

    public function getDataStructure()
    {
        if ($this->getData()->valid() && $this->iteratorMode == DirectoryIterator::MODE_DATA) {
            return DocumentStructureReader::readStructureFromArray($this->getData()->current());
        } else {
            return null;
        }

    }

    public function getDataType()
    {
        return (($this->iteratorMode == DirectoryIterator::MODE_DATA))?
            static::DATA_TYPE_DOCUMENT_STRUCTURE : static::DATA_TYPE_NOT_DEFINED;
    }

    public function getItemCount()
    {
        return count($this->getFilenames());
    }

    public function getOptions()
    {
        return array(
            'source_dir' => $this->sourceDir,
            'file_pattern' => $this->filePattern,
            'iterator_mode' => $this->iteratorMode,
        );
    }

    public function setOptions(array $options)
    {
        $this->sourceDir = $options['source_dir'];
        $this->filePattern = $options['file_pattern'];

        if (isset($options['iterator_mode'])) {
            $this->iteratorMode = $options['iterator_mode'];
        }

        $this->directoryIterator = null;
        $this->filenames = null;
    }

    protected function getFilenames()
    {
        if (is_null($this->filenames)) {
            $this->filenames = AbstractDirectory::getFilenames($this->getDir(), $this->filePattern);
        }

        return $this->filenames;
    }

    protected function getDir()
    {
        if ($this->sourceDir == "inbox") {
            return Inbox::DIR;
        } else if ($this->sourceDir == "outbox") {
            return Outbox::DIR;
        } else {
            return $this->sourceDir;
        }
    }
}