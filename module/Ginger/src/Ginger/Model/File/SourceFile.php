<?php
namespace Ginger\Model\File;

use Ginger\Model\Source\AbstractSource;
use Ginger\Service\DataStructure\DocumentStructureReader;
use Ginger\Model\Directory\Inbox;
use Zend\Stdlib\ArrayUtils;
/**
 * Description of SourceFile
 *
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 * @copyright (c) 2013, Alexander Miertsch
 */
class SourceFile extends AbstractSource
{
    protected  $filePattern = '/^.+\..+$/';
    protected  $dataStructure;
    protected  $count;
    protected  $data;
    protected $file;

    public function getData()
    {
        if (is_null($this->data)) {
            $filename = Inbox::getFirstFileName($this->filePattern);

            if (!$filename) {
                throw new Exception\FileNotFoundException(
                    sprintf('Can not find a file in inbox with pattern: "%s"',
                        $this->filePattern
                        ));
            }

            $this->file = new File(Inbox::DIR, $filename);

            $data = $this->file->getData();

            if (empty($data)) {
                throw new Excetpion\EmptyFileException(
                    sprintf(
                        'The inbox file "%s" contains no data.',
                        $filename
                        )
                    );
            }

            //Always work with a list, to make sure that we don't iterate over key/value in a job
            //in this case, we assume, that the file only contains one dataset
            if (!ArrayUtils::isList($data)) {
                $data = array($data);
            }

            $this->data = $data;
        }

        return $this->data;
    }

    public function getDataStructure()
    {
        if (is_null($this->dataStructure)) {

            $data = $this->getData();

            //we only need the structure of one collection element
            $this->dataStructure = DocumentStructureReader::readStructureFromArray($data[0]);
        }

        return $this->dataStructure;
    }

    public function getDataType()
    {
        return static::DATA_TYPE_DOCUMENT_STRUCTURE;
    }

    public function getItemCount()
    {
        if (is_null($this->count)) {
            $this->count = count($this->getData());
        }

        return $this->count;
    }

    public function getOptions()
    {
        return array(
            'file_pattern' => $this->filePattern,
        );
    }

    public function setOptions(array $options)
    {
        $this->filePattern = $options['file_pattern'];
    }
}