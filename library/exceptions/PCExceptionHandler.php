<?php

/**
 * Description of PCExcpetionManager
 *
 * @author paolo
 */
class PCExceptionHandler {
    /**
     *
     * @param PCException $ex 
     */
    public static function handleException($ex){
        if($ex->getCode() == 404){
            header('HTTP/1.1 404 Not Found');
        }
        
        $request = PCRequest::currentRequest();
        $type = $request->getRequestType();
        
        $response = PCResponse::currentResponse();
        $response->setResponseCode($ex->getCode());
        
        $renderer = NULL;
        
        if($type == PCRequest::TYPE_AJAX){     
            $result = array('error' => $ex->getMessage());
            $renderer = new PCRendererJSON($result,404);
        }
        else if($type == PCRequest::TYPE_WEB){
            $renderer = PCRendererHTML::rendererForView('404', array('title' => "Sorry, page not found :(") );
        }
        else if($type == PCRequest::TYPE_API){
            die('API NOT YET SUPPORTED');
        }
        else{
            die("UNKNOWN REQUEST TYPE: $type");
        }
        $response->setRenderer($renderer);
        $response->sendResponse();
        
        if(defined('DEBUG')){
            error_log($ex->getMessage());
            error_log($ex->getTraceAsString());
        }
         
    }
}