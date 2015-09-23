<?

abstract class PCDatabaseQuery {
    
    /**
     * Il nome della tabella
     * @var string 
     */
    protected $table_name;
    
     /**
     *
     * @var array
     */
    protected $items = NULL;
    
    /**
     *
     * @var PCDatabaseQueryCondition
     */
    protected $condition;
    
    /**
     *
     * @var array per prepared queries
     */
    protected $bindings;
    
    /**
     *
     * @var boolean
     */
    protected $is_prepared = TRUE;


    /**
     * 
     * @param string $table_name
     * @param array $items
     * @param PCDatabaseQueryCondition $condition
     */
    function __construct($table_name, $items, $condition = NULL) {
        $this->table_name = $table_name;
        $this->condition = $condition;
    }

    /**
     * 
     * @return array
     */
    public function getItems() {
        return $this->items;
    }

    /**
     * 
     * @param array $items
     */
    public function setItems($items) {
        $this->items = $items;
    }
    
    /**
     * 
     * @return PCDatabaseQueryCondition
     */
    public function getCondition() {
        return $this->condition;
    }

    /**
     * 
     * @param PCDatabaseQueryCondition $condition
     */
    public function setCondition( $condition) {
        $this->condition = $condition;
    }

    
    /**
     * 
     * @return string
     */
    public function getTable_name() {
        return $this->table_name;
    }

    /**
     * 
     * @param string $table_name
     */
    public function setTable_name($table_name) {
        $this->table_name = $table_name;
    }

    /**
     * 
     * @return array
     */
    public function getBindings(){
        return $this->bindings;
    }
    
    public function setBindings($bindings) {
        $this->bindings = $bindings;
    }

    /**
     * 
     * @return boolean
     */
    public function isPrepared() {
        return $this->is_prepared;
    }

    /**
     * 
     * @param boolean $is_prepared
     */
    public function setIsPrepared($is_prepared) {
        $this->is_prepared = $is_prepared;
    }

    
    public abstract function toSQL();
    

}

