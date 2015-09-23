<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of PCAjaxCategoriesController
 *
 * @author paolo
 */
class PCAjaxCategoriesController extends PCController{
    
    public function allAction($request){
       
        $all = PCMapperCategory::getAll();
        
        return new PCRendererJSON($all);
        
    }
    
    public function supportsHTTPMethod($method) {
        if($method == PCRequest::HTTP_METHOD_GET){
            return TRUE;
        }
        return parent::supportsHTTPMethod($method);
    }
    
}

?>
