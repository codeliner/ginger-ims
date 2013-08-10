<?php
namespace SqlConnect\Model\Db;

use Ginger\Model\Source\AbstractSource;
use Ginger\Service\DataStructure\TableStructureNormalizer;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\ORM\AbstractQuery;
use SqlConnect\Service\Db\AdapterFactory;

/**
 * Description of TableSource
 *
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 * @copyright (c) 2013, Alexander Miertsch
 */
class TableSource extends AbstractSource implements ServiceLocatorAwareInterface
{
    /**
     * @var string
     */
    protected $table;

    /**
     *
     * @var string
     */
    protected $connection;

    /**
     * @var ServiceLocatorInterface
     */
    protected $serviceLocator;

    protected $countColumn = "id";

    protected $customSql = "";

    /**
     *
     * @var QueryBuilder
     */
    protected $adapter;

    protected function init()
    {
        $this->extractInfo($this->name);
    }

    public function getItemCount()
    {
        if ($this->customSql == "") {
            $rowCount = $this->getAdapter()->from($this->table, 'a')
                ->select('count(a.' . $this->countColumn . ')')
                ->execute()->fetchColumn();
        } else {
            $rowSet = $this->getAdapter()->getConnection()->query($this->customSql);
            $rowCount = 0;

            foreach ($rowSet as $row) {
                $rowCount++;
            }
        }

        return $rowCount;
    }

    public function getOptions()
    {
        $options = array('countColumn' => $this->countColumn);

        if ($this->customSql != "") {
            $options['customSql'] = $this->customSql;
        }

        return $options;
    }

    public function setOptions(array $options)
    {
        if (isset($options['countColumn'])) {
            $this->countColumn = $options['countColumn'];
        }

        if (isset($options['customSql'])) {
            $this->customSql = (string)$options['customSql'];
        }
    }

    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }

    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
    }

    public function getData()
    {

            if ($this->customSql == "") {
                $rowSet = $this->getAdapter()->from($this->table, 'a')
                    ->select('a.*')
                    ->execute();
            } else {
                $rowSet = $this->getAdapter()->getConnection()->query($this->customSql);
            }



        return $rowSet;
    }

    public function getDataStructure()
    {
        $dataStructure = $this->getAdapter()
            ->getConnection()
            ->getDriver()
            ->getSchemaManager($this->getAdapter()->getConnection())
            ->listTableColumns($this->table);

        return TableStructureNormalizer::columnsToArray($dataStructure);
    }

    public function getDataType()
    {
        return static::DATA_TYPE_TABLE_STRUCTURE;
    }

    /**
     * @return QueryBuilder
     */
    protected function getAdapter()
    {
        if (is_null($this->adapter)) {

            /* @var $adapterFactory AdapterFactory */
            $adapterFactory = $this->serviceLocator->get('sqlconnect_adapter_factory');

            $this->adapter = $adapterFactory->factory($this->connection);
        }

        return $this->adapter;

    }

    protected function extractInfo($elementName)
    {
        $parts = explode('::', $elementName);
        $this->connection = $parts[0];
        $this->table = $parts[1];
    }
}