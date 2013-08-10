<?php
namespace Cl\Filesystem;
/**
 * Description of DirectoryManager
 *
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 * @copyright (c) 2013, Alexander Miertsch
 */
class DirectoryManager
{
    public static function recursiveRemoveDir($dir)
    {
        if (is_dir($dir)) {
            $objects = scandir($dir);
            foreach ($objects as $object) {
                if ($object != "." && $object != "..") {
                    if (filetype($dir . '/' . $object) == "dir") {
                        self::recursiveRemoveDir($dir . '/' . $object);
                    } else {
                        unlink($dir . '/' . $object);
                    }
                }
            }
            reset($objects);
            return rmdir($dir);
        }

        return false;
    }

    public static function copyDir($src, $dest)
    {
        if (is_dir($src)) {
            $objects = scandir($src);
            mkdir($dest);
            foreach ($objects as $object) {
                if (strpos($object, ".") !== 0) {
                    if (filetype($src . '/' . $object) == "dir") {
                        self::copyDir($src . '/' . $object, $dest . '/' . $object);
                    } else {
                        copy($src . '/' . $object, $dest . '/' . $object);
                    }
                }
            }
            return true;
        }

        return false;
    }
}