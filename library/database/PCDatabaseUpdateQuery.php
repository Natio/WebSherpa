<?

class PCDatabaseUpdateQuery extends PCDatabaseQuery {

    /**
     * 
     * @param PCMapper $mapper
     * @param array $values
     * @param PCDatabaseQueryCondition $condition
     * @return PCDatabaseUpdateQuery
     */
    public static function withMapper($mapper, $values, $condition = NULL) {
        return new PCDatabaseUpdateQuery($mapper->getTableForInsertUpdate(), $values, $condition);
    }

    public function toSQL() {

        $ret = 'UPDATE ' . $this->table_name . ' SET ';
        $isFirst = TRUE;
        foreach ($this->items as $key => $value) {
            if ($isFirst) {
                $isFirst = FALSE;
                $ret .= " $key = $value ";
            } else {
                $ret .= " ,$key = $value ";
            }
        }

        if (isset($this->condition)) {
            $ret .= " WHERE " . $this->condition;
        }
        $ret .= ';';

        return $ret;
    }
    

}