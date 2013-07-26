<?php
namespace Ginger\Model\File\Writer;
/**
 * Description of WriterInteface
 *
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 * @copyright (c) 2013, Alexander Miertsch
 */
interface WriterInterface
{
    /**
     * Write a data array to a file.
     *
     * @param  string  $filename
     * @param  array   $data
     * @param  boolean $exclusiveLock
     * @return void
     */
    public function toFile($filename, array $config, $exclusiveLock = true);


    /**
     * Add an entry to a data file
     *
     * @param  type  $filename
     * @param  mixed $entry
     * @return void
     */
    public function addToFile($filename, $entry);
}