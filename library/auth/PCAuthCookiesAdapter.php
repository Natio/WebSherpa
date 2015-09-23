<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 *
 * @author paolo
 */
interface PCAuthCookiesAdapter {
    
    
    /**
     * 
     * @param array $cokies
     * @param PCModelApplication $application
     * @return boolean
     */
    public function autorizeWithCookies($cokies,$application, &$user_id);
    
    /**
     * 
     * @param PCRequest $request
     * @param PCModelApplication $application
     */
    public function doLogin($request, $application);
    
    /**
     * 
     * @param PCModelApplication $application
     */
    public function doLogout($application);
}

?>
