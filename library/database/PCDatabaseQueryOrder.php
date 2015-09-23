<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of PCDatabaseQueryOrder
 *
 * @author paolo
 */
class PCDatabaseQueryOrder {
   
    /**
     *
     * @var string 
     */
    private $fieldName;

    /**
     *
     * @var boolean 
     */
    private $ascending;
    
    /**
     * 
     * @param type $fieldName
     * @param type $ascending
     */
    function __construct($fieldName, $ascending = TRUE) {
        $this->fieldName = $fieldName;
        $this->ascending = $ascending;
    }

    /**
     * 
     * @return string
     */
    public function getFieldName() {
        return $this->fieldName;
    }

    /**
     * 
     * @return boolean
     */
    public function isAscending() {
        return $this->ascending;
    }

    public function __toString() {
        return $this->fieldName.' '.($this->ascending ? 'ASC':'DESC');
    }


}

?>
