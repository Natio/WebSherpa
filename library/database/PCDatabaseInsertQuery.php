<?

class PCDatabaseInsertQuery extends PCDatabaseQuery{
    
    /**
     *
     * @var PCDatabaseUpdateQuery
     */
    protected $on_duplicate_keys;

    /**
     * 
     * @return PCDatabaseUpdateQuery
     */
    public function getDuplicateKeysUpdateQuery() {
        return $this->on_duplicate_keys;
    }

    /**
     * 
     * @param PCDatabaseUpdateQuery $on_duplicate_keys
     */
    public function setDuplicateKeysUpdateQuery(PCDatabaseUpdateQuery $on_duplicate_keys) {
        $this->on_duplicate_keys = $on_duplicate_keys;
    }

    
    public function toSQL() {
        $ret = 'INSERT INTO '.$this->table_name;
        $cols = "";
        $vals = "";
        $first = TRUE;
        foreach ($this->items as $key => $value) {
            if($first){
                $first = FALSE;
                $cols .= "$key ";
                $vals .= "$value ";
            }
            else{
                $cols .= ",$key ";
                $vals .= ",$value ";
            }
        }
        
        $ret .= " ( $cols ) VALUES ( $vals ) ";
        
        if(isset($this->on_duplicate_keys)){
            $this->on_duplicate_keys->table_name = '';
            $ret .= $this->on_duplicate_keys->toSQL();
        }
        $ret.=";";
        
        return $ret;
    }    
}

