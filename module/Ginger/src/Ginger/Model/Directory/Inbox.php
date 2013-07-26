<?php
namespace Ginger\Model\Directory;
/**
 * Description of Inbox
 *
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 * @copyright (c) 2013, Alexander Miertsch
 */
class Inbox
{
    const DIR = "data/inbox";

    public static function getFirstFileName($pattern)
    {
        return AbstractDirectory::getFirstFileName(static::DIR, $pattern);
    }

    public static function getFilenames($pattern = null)
    {
        return AbstractDirectory::getFilenames(static::DIR, $pattern);
    }

    public static function deleteFiles($pattern = null)
    {
        return AbstractDirectory::deleteFiles(static::DIR, $pattern);
    }
}