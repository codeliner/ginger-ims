<?php
namespace Ginger\Model\File;

use Zend\ServiceManager\AbstractPluginManager;
/**
 * Description of WriterPluginManager
 *
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 * @copyright (c) 2013, Alexander Miertsch
 */
class WriterPluginManager extends AbstractPluginManager
{
    /**
     * Default set of readers
     *
     * @var array
     */
    protected $invokableClasses = array(
        'csv'  => 'Ginger\Model\File\Writer\Csv',
        'json' => 'Ginger\Model\File\Writer\Json',
        'xml'  => 'Ginger\Model\File\Writer\Xml',
    );

    /**
     * Validate the plugin
     * Checks that the writer loaded is an instance of WriterInterface.
     *
     * @param  Writer\WriterInterface $plugin
     * @return void
     * @throws Exception\InvalidArgumentException if invalid
     */
    public function validatePlugin($plugin)
    {
        if ($plugin instanceof Writer\WriterInterface) {
            // we're okay
            return;
        }

        throw new Exception\InvalidArgumentException(sprintf(
            'Plugin of type %s is invalid; must implement Ginger\Model\File\Writer\WriterInterface',
            (is_object($plugin) ? get_class($plugin) : gettype($plugin)),
            __NAMESPACE__
        ));
    }
}