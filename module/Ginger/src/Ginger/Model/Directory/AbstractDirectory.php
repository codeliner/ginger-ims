<?php
namespace Ginger\Model\Directory;
/**
 * Description of AbstractDirectory
 *
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 * @copyright (c) 2013, Alexander Miertsch
 */
class AbstractDirectory
{
    public static function getFirstFileName($path, $pattern)
    {
        $objects = scandir($path);

        foreach ($objects as $filename) {
            if (strpos($filename, '.') === 0) {
                continue;
            }
            if (preg_match($pattern, $filename)) {
                return $filename;
            }
        }
    }

    public static function getFilenames($path, $pattern = null)
    {
        $objects = scandir($path);

        $filenames = array();

        foreach($objects as $filename) {
            if (strpos($filename, '.') === 0) {
                continue;
            }

            if (!is_null($pattern)) {
                if (!preg_match($pattern, $filename)) {
                    continue;
                }
            }

            if (is_file($path . '/' . $filename)) {
                $filenames[] = $filename;
            }
        }

        return $filenames;
    }

    public static function deleteFiles($path, $pattern = null)
    {
        $fileNames = static::getFilenames($path, $pattern);

        foreach ($fileNames as $fileName) {
            unlink($path . '/' . $fileName);
        }

        return count($fileNames);
    }
}