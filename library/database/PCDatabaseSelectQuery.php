<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of PCDatabaseSelectQuery
 *
 * @author paolo
 */
class PCDatabaseSelectQuery extends PCDatabaseQuery {

    /**
     * Query limit
     * @var PCDatabaseQueryLimit  
     */
    protected $limit = NULL;

   

    /**
     *
     * @var PCDatabaseQueryOrder
     */
    protected $oder = NULL;

    /**
     * 
     * @param string $table
     * @param array $items
     * @param PCDatabaseQueryCondition $condition
     * @param PCDatabaseQueryLimit $limit
     * @param PCDatabaseQueryOrder $oder
     */
    function __construct($table,  $items, $condition, $limit = NULL, $oder = NULL) {
        parent::__construct($table, $items, $condition);
        $this->limit = $limit;
        $this->items = $items;
        $this->oder = $oder;
    }
    
    /**
     * 
     * @param PCMapper $mapper
     * @param PCDatabaseQueryCondition $condition
     * @param PCDatabaseQueryLimit $limit
     * @param PCDatabaseQueryOrder $oder
     */
    public static function withMapper($mapper, $condition = NULL, $limit = NULL, $order = NULL){
        return new PCDatabaseSelectQuery($mapper->getTableName(), $mapper->getRequiredAttributes(), $condition, $limit, $order);
    }

    /**
     * 
     * @return PCDatabaseQueryLimit
     */
    public function getLimit() {
        return $this->limit;
    }

    /**
     * 
     * @param PCDatabaseQueryLimit $limit
     */
    public function setLimit($limit) {
        $this->limit = $limit;
    }

    
    /**
     * 
     * @return PCDatabaseQueryOrder
     */
    public function getOder() {
        return $this->oder;
    }

    /**
     * 
     * @param PCDatabaseQueryOrder $oder
     */
    public function setOder($oder) {
        $this->oder = $oder;
    }

    /**
     * @return string
     */
    public function toSQL() {

        if (!isset($this->table_name) || !isset($this->items) || count($this->items) == 0) {
            throw new PCException("Table name: $this->table_name è null oppure items è vuoto: " . count($this->items));
        }

        $select = "SELECT " . implode(",", $this->items) . " FROM " . $this->table_name . " WHERE " . (isset($this->condition) ? $this->condition : 'TRUE');

        if (isset($this->oder)) {
            $select .= " ORDER BY " . $this->oder;
        }

        if (isset($this->limit)) {
            $select .= " LIMIT " . $this->limit;
        }



        return $select . ';';
    }

}

