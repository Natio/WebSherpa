<?

/**
 * Description of PCMapperCategory
 *
 * @author paolo
 */
class PCMapperCategory extends PCMapper {
    
    public function getMappedInstance($attributes) {
        $identifier = $attributes['identifier'];
        $name = $attributes['name'];
        return new PCModelCategory($identifier, $name);
    }

    public function getRequiredAttributes() {
        return array("identifier", "name");
    }

    public function getTableName() {
        return "website_category_tbl";
    } 
    
    public static function getAll(){
        $order = " name";
        return PCModelManager::fetchModelObjectInstances(PCModelCategory::getMapper(), array(), null, true, null, $order);
    }
    
    public static function nameFromIdentifier($identifier){
        $category = PCModelManager::fetchObjectWithIdentifier(PCModelCategory::getMapper(), $identifier, NULL, TRUE);
        return $category->getName();
    }
    
    /**
     * Returns TRUE if the category exists
     * @param string $identifier
     * @retrun bool
     */
    public static function existsCategoryWithIdentifier($identifier){
        $category = PCModelManager::fetchObjectWithIdentifier(PCModelCategory::getMapper(), $identifier, NULL, TRUE);
        return isset($category);
    }
}
