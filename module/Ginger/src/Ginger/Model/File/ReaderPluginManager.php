<?php
namespace Ginger\Model\File;

use Zend\ServiceManager\AbstractPluginManager;
/**
 * Description of ReaderPluginManager
 *
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 * @copyright (c) 2013, Alexander Miertsch
 */
class ReaderPluginManager extends AbstractPluginManager
{
    /**
     * Default set of readers
     *
     * @var array
     */
    protected $invokableClasses = array(
        'csv'  => 'Ginger\Model\File\Reader\Csv',
        'json' => 'Ginger\Model\File\Reader\Json',
        'xml'  => 'Ginger\Model\File\Reader\Xml',
    );

    /**
     * Validate the plugin
     * Checks that the reader loaded is an instance of ReaderInterface.
     *
     * @param  ReaderInterface $plugin
     * @return void
     * @throws Exception\InvalidArgumentException if invalid
     */
    public function validatePlugin($plugin)
    {
        if ($plugin instanceof Reader\ReaderInterface) {
            // we're okay
            return;
        }

        throw new Exception\InvalidArgumentException(sprintf(
            'Plugin of type %s is invalid; must implement Ginger\Model\File\Reader\ReaderInterface',
            (is_object($plugin) ? get_class($plugin) : gettype($plugin)),
            __NAMESPACE__
        ));
    }
}