<?

function c_dump($var){
    ob_start();
    var_dump($var);
    $contents = ob_get_contents();
    ob_end_clean();
    error_log($contents);
}

define('__ROOT__', (dirname(__FILE__)));
if(strcmp($_SERVER['HTTP_HOST'], 'websherpa.loc') == 0) define('DEBUG', TRUE);

include(__ROOT__ . '/library/helpers/PCConfigManager.php');

PCConfigManager::sharedManager()->readConfigurationFromGET(TRUE);

include (__ROOT__ . "/library/bootstrap.php");


try {
   
    $request  = PCRequest::currentRequest();
    $item = PCRouter::sharedRouter()->route($request);
    if($item instanceof PCRenderer){
        $response = PCResponse::currentResponse();
        $response->setRenderer($item);
        $response->sendResponse();
    }
    else if($item instanceof PCResponse){
        $item->sendResponse();
    }
    else if($item == NULL){
        throw new PCExceptionRoute("Page not found", 404);
    }
    else{
         throw new PCExceptionRoute("Errore interno", 500);
    }
}
catch (PCExceptionRedirection $exc){
    //redirects
    $exc->redirect();
}
catch (PCException $exc) {
   
    PCExceptionHandler::handleException($exc);
   
}
catch( Exception $e){
    error_log("Message: " . $e->getMessage());
    error_log($e->getTraceAsString());
    http_response_code(500);
}

/*ob_start();
var_dump($_SERVER);
$contents = ob_get_contents();
ob_end_clean();
error_log($contents);*/