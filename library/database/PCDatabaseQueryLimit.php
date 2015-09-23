<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of PCDatabaseQueryLimit
 *
 * @author paolo
 */
class PCDatabaseQueryLimit {
    /**
     *
     * @var int
     */
    private $offset;
    /**
     *
     * @var int
     */
    private $lenght;
    
    function __construct($offset, $lenght) {
        $this->offset = $offset;
        $this->lenght = $lenght;
    }

    public function getOffset() {
        return $this->offset;
    }

    public function getLenght() {
        return $this->lenght;
    }

    public function __toString() {
        return "$this->offset, $this->lenght";
    }

}

?>
