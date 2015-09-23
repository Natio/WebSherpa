<?

/**
 * PCMapper maps db do model and vice versa
 *
 * @author paolo
 */
abstract class PCMapper {
    
   
    /**
     * @return string a string containing the name of the table in the db
     */
    public abstract function getTableName();
    /**
     * @return array an array containing the required attributes of the object to map
     */
    public abstract function getRequiredAttributes();


    
    public abstract function getMappedInstance($attributes);
    
    /**
     * @param array $attributes
     * @return string|null
     */
    public function getCacheKey($attributes) {
        if(isset($attributes['identifier'])){
            return $this->getTableName().$attributes['identifier'];
        }
        return NULL;
    }  
    
    /**
     * Returns the table for insert or update (if not overridden returns the same as getTableName() )
     * @return string
     */
    public function getTableForInsertUpdate(){
        return $this->getTableName();
    }
    
    /**
     * @param $identifier string
     */
    public function withIdentifier($identifier, $optional_attributes = null, $use_cache = FALSE){
        return PCModelManager::fetchModelObjectInstances($this, array("identifier"=>$identifier), $optional_attributes, $use_cache);
    }
}