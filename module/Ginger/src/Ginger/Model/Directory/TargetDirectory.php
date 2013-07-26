<?php
namespace Ginger\Model\Directory;

use Ginger\Model\Target\AbstractTarget;
use Ginger\Model\File\File;
use Ginger\Service\DataStructure\PlaceholderTool;
use Ginger\Model\Connector\Exception\InvalidItemTypeException;
/**
 * Description of TargetDirectory
 *
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 * @copyright (c) 2013, Alexander Miertsch
 */
class TargetDirectory extends AbstractTarget
{
    protected $targetDir = 'outbox';

    protected $filenamePattern;

    protected $action = "write";

    public function addItem($item)
    {
        if ($item instanceof File) {
            $item->move($this->getDir());

            if (!empty($this->filenamePattern)) {

                //First we assume, that filenamePattern contains the hole name, may this will be overriden
                //in the next few lines
                $filename = $this->filenamePattern;

                //Do we need to replace some keys in the filename?
                $placeholderKeys = PlaceholderTool::readPlaceholderKeys($this->filenamePattern);

                if (count($placeholderKeys)) {
                    $defaultKeys = array('$DATE', '$TIME', '$DATETIME');
                    $dataKeyFound = false;

                    foreach($placeholderKeys as $key) {
                        if (!in_array($key, $defaultKeys)) {
                            $dataKeyFound = true;
                            break;
                        }
                    }

                    //Only read data from file, if we realy need to replace a data placeholder
                    if ($dataKeyFound) {
                        $data = $item->getData();
                    } else {
                        $data = array();
                    }

                    $filename = $this->generateFilename($data);
                }

                //rename the moved file
                $item->rename($filename);
            }
        } else if (is_array($item)) {
            $file = new File($this->getDir(), $this->generateFilename($item));
            $file->writeData($item);
        } else {
            throw new InvalidItemTypeException(
                sprintf(
                    '%s can only work with files or arrays.',
                    get_class($this)
                    )
                );
        }
    }

    public function getDataStructure()
    {
        return null;
    }

    public function getDataType()
    {
        return static::DATA_TYPE_NOT_DEFINED;
    }

    public function getOptions()
    {
        return array(
            'target_dir' => $this->targetDir,
            'filename_pattern' => $this->filenamePattern,
        );
    }

    public function setOptions(array $options)
    {
        $this->targetDir = $options['target_dir'];
        $this->filenamePattern = $options['filename_pattern'];
    }

    protected function getDir()
    {
        if ($this->targetDir == "inbox") {
            return Inbox::DIR;
        } else if ($this->targetDir == "outbox") {
            return Outbox::DIR;
        } else {
            return $this->targetDir;
        }
    }

    protected function generateFilename(array $data)
    {
        $placeholderKeys = PlaceholderTool::readPlaceholderKeys($this->filenamePattern);

        if (count($placeholderKeys)) {
            $placeholderMap = array();

            foreach($placeholderKeys as $key) {
                switch ($key) {
                    case '$DATE':
                        $placeholderMap[$key] = date('Y-m-d');
                        break;
                    case '$TIME':
                        $placeholderMap[$key] = date('H-i-s');
                        break;
                    case '$DATETIME':
                        $placeholderMap[$key] = date('Y-m-d-H-i-s');
                        break;
                    default:
                        $placeholderMap[$key] = $this->getAttributeValue(static::DATA_TYPE_DOCUMENT_STRUCTURE, $key, $data);
                }
            }

            return PlaceholderTool::replace($this->filenamePattern, $placeholderMap);
        } else {
            return $this->filenamePattern;
        }
    }
}