<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of PCDatabaseQueryCondition
 *
 * @author paolo
 */
class PCDatabaseQueryCondition {
    
    const EQUALS = '=';
    const NOT_EQUALS = '!=';
    const GREATER = '>';
    const LESS = '<';
    const EQUALS_OR_LESS = '<=';
    const EQUALS_OR_GREATER = '>=';
    
    private static $AND = 'AND';
    private static $OR = 'OR';

    /**
     *
     * @var PCDatabaseQueryCondition|string
     */
    private $leftOperand;
    
    /**
     *
     * @var PCDatabaseQueryCondition|string
     */
    private $rightOperand;
    
    /**
     * @var string 
     */
    private $operation;
    
    public function __construct($left, $operation, $right) {
        $this->leftOperand = $left;
        $this->operation = $operation;
        $this->rightOperand = $right;
    }
    
    /**
     * 
     * @param PCDatabaseQueryCondition $left
     * @param PCDatabaseQueryCondition $right
     * @return PCDatabaseQueryCondition
     */
    public static function orCondition($left, $right){
        return new PCDatabaseQueryCondition($left, static::$OR, $right);
    }
    /**
     * 
     * @param PCDatabaseQueryCondition $left
     * @param PCDatabaseQueryCondition $right
     * @return PCDatabaseQueryCondition
     */
    public static function andCondition($left, $right){
        return new PCDatabaseQueryCondition($left, static::$AND, $right);
    }
    
    public function __toString() {
        $left = ($this->leftOperand instanceof PCDatabaseQueryCondition) ? "(" .$this->leftOperand. ")" : $this->leftOperand;
        $right = ($this->rightOperand instanceof PCDatabaseQueryCondition) ? "(" .$this->rightOperand. ")" : $this->rightOperand ;
        return "$left $this->operation $right";
    }
}

?>
