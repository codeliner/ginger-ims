<?php

/**
 * TableGateway Mock
 * 
 * @author Alexander Miertsch kontakt@codeliner.ws
 * @package Cl_Test
 * @subpackage Mock
 * @copyright 2012 Fenske und Miertsch GbR
 */
namespace Cl\Test\Mock;

class TableGatewayMock
{
    protected $_selectList = array();

    public function __construct($selectList = array())
    {
        $this->_selectList = $selectList;
    }


    public function getTable()
    {
        return 'mock_table';
    }    
    
    /**
     * Returns first element from select list, and remove this element from the list
     *
     * @param mixed $where
     * 
     * @return mixed 
     */
    public function select($where = null)
    {
        return array_shift($this->_selectList);
    }
    
    public function insert($set)
    {
        return;
    }
    
    public function update($set, $where = null)
    {
        return;
    }
    
    public function delete($where)
    {
        return;
    }
}