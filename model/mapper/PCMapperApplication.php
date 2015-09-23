<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of PCMapperApplication
 *
 * @author paolo
 */
class PCMapperApplication extends PCMapper {
    
    
    
    public function getMappedInstance($attributes) {
        $identifier = $attributes['identifier'];
        $secret = $attributes['secret'];
        return new PCModelApplication($identifier,$secret);
    }

    public function getRequiredAttributes() {
        return array("identifier","secret");
    }

    public function getTableName() {
        return "applications_tbl";
    }

      
}

?>
