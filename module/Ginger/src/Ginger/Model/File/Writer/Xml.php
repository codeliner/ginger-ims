<?php
namespace Ginger\Model\File\Writer;

use Zend\Stdlib\ArrayUtils;
use XMLWriter;
use Ginger\Model\File\Reader\Xml as XmlReader;
/**
 * Description of Xml
 *
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 * @copyright (c) 2013, Alexander Miertsch
 */
class Xml extends AbstractWriter
{
    protected $listName = 'items';

    protected $itemName = 'item';

    protected $dataPaths = null;

    protected $reader = null;

    public function addToFile($filename, $entry)
    {
        $reader = $this->getReader();
        $data = $reader->fromFile($filename);
        $data[] = $entry;
        $this->toFile($filename, $data);
    }

    public function getListName()
    {
        return $this->listName;
    }

    public function setListName($listName)
    {
        $this->listName = $listName;
    }

    public function getItemName()
    {
        return $this->itemName;
    }

    public function setItemName($itemName)
    {
        $this->itemName = $itemName;
    }

    public function getDataPaths()
    {
        return $this->dataPaths;
    }

    public function setDataPaths($dataPaths)
    {
        $this->dataPaths = $dataPaths;
    }

    protected function processData(array $data)
    {
        $writer = new XMLWriter('UTF-8');
        $writer->openMemory();
        $writer->setIndent(true);
        $writer->setIndentString(str_repeat(' ', 4));

        $writer->startDocument('1.0', 'UTF-8');

        $isList = ArrayUtils::isList($data, true);

        $startElement = $isList? $this->listName : $this->itemName;

        $writer->startElement($startElement);

        foreach ($data as $sectionName => $dataset) {

            if ($isList) {
                $sectionName = $this->itemName;
                $path = $startElement . '::[' . $this->itemName . ']';
            } else {
                $path = $startElement . '::' . $sectionName;
            }

            if (!is_array($dataset)) {
                $writer->writeElement($sectionName, (string) $dataset);
            } else {
                $this->addBranch($sectionName, $dataset, $writer, $path);
            }
        }

        $writer->endElement();
        $writer->endDocument();

        return $writer->outputMemory();
    }

    /**
     * Add a branch to an XML object recursively.
     *
     * @param  string    $branchName
     * @param  array     $data
     * @param  XMLWriter $writer
     * @param  string    $path
     *
     * @return void
     * @throws Exception\RuntimeException
     */
    protected function addBranch($branchName, array $data, XMLWriter $writer, $path)
    {
        $branchType = null;

        $writer->startElement($branchName);

        foreach ($data as $key => $value) {
            if ($branchType === null) {
                if (is_numeric($key)) {
                    $branchType = 'numeric';
                } else {
                    $branchType = 'string';

                }
            } elseif ($branchType !== (is_numeric($key) ? 'numeric' : 'string')) {
                throw new Exception\RuntimeException('Mixing of string and numeric keys is not allowed');
            }

            if ($branchType === 'numeric') {
                $listBranchName = $this->getListBranchName($path);

                if (is_array($value)) {
                    $isList = ArrayUtils::isList($value);

                    $writer->startElement($listBranchName);

                    $nextPath = $path . '::[' . $listBranchName . ']';

                    foreach ($value as $sectionName => $dataset) {
                        if ($isList) {
                            $sectionName = $this->getListBranchName($nextPath);
                            $itemPath = $nextPath . '::[' . $sectionName . ']';
                        } else {
                            $itemPath = $nextPath . '::' . $sectionName;
                        }

                        if (!is_array($dataset)) {
                            $writer->writeElement($sectionName, (string) $dataset);
                        } else {
                            $this->addBranch($sectionName, $dataset, $writer, $itemPath);
                        }
                    }

                    $writer->endElement();
                } else {
                    $writer->writeElement($listBranchName, (string) $value);
                }

            } else {
                if (is_array($value)) {
                    $this->addBranch($key, $value, $writer, $path . '::' . $key);
                } else {
                    $writer->writeElement($key, (string) $value);
                }
            }
        }

        $writer->endElement();
    }

    protected function getListBranchName($path)
    {
        if (!is_null($this->getDataPaths())) {
            $dataStructure = $this->getDataPaths();

            //Search for the beginning of the branch in dataStructure
            $branchPos = array_search($path, $dataStructure);

            if ($branchPos !== false) {
                //Go one step forward to get the next level, this should be the path to the list
                $itemPath = $dataStructure[$branchPos + 1];

                //Check if we realy working with the list path, may it contains a definition for the item name in the list
                if ($itemPath && strpos($itemPath, $path . '::[') === 0) {
                    $matches = array();

                    preg_match('/^.+\:\:\[(?P<itemName>[^\]]+)\]$/', $itemPath, $matches);

                    if (isset($matches['itemName'])) {
                        return $matches['itemName'];
                    }
                }
            }
        }

        return 'item';
    }

    protected function getReader()
    {
        if (is_null($this->reader)) {
            $this->reader = new XmlReader();
        }

        return $this->reader;
    }
}