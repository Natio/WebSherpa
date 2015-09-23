<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of PCRouter
 *
 * @author paolo
 */
class PCRouter {
    /**
     * The base directory for the controllers
     * @var string 
     */
    private $controllersBaseDirectory = "";
    /**
     * an array of all the routes
     * @var array
     */
    private $routes;
    
    /**
     * an array of regexes associated within a key
     * @var array 
     */
    private $typesRegexes;
    /**
     *  The shared instance of PCRouter
     * @var PCRouter 
     */
    
    private static $sharedRouter;
    
    private function __construct() {
        $this->routes = array();
        $this->typesRegexes = array();
    }
    
    
    /**
     * Returna the shared router instance
     * @return PCRouter
     */
    public static function sharedRouter(){
        if(static::$sharedRouter == NULL){
            static::$sharedRouter = new PCRouter();
        }
        return static::$sharedRouter;
    }
    
    /**
     * returns the controller base directory
     * @return string
     */
    public function getControllersBaseDirectory() {
        return $this->controllersBaseDirectory;
    }
    /**
     * Sets the controllers base directory
     * @param string $controllersBaseDirectory
     */
    public function setControllersBaseDirectory($controllersBaseDirectory) {
        $this->controllersBaseDirectory = $controllersBaseDirectory;
    }

        
    public function addSubdomain($regex, $name){
        $this->typesRegexes[$name] = $regex;
        $this->routes[$name] = array();
    }
    
    /**
     * Adds a route to the router
     * @param PCRouterRoute $route
     * @param string $domain the route domain name
     */
    public function addRoute($route, $domain){
        
        $this->routes[$domain][$route->getName()] = $route;
    }
    /**
     * Returns an instancied response
     * @param PCRequest $request
     * @return PCResponse returns an instancied response 
     */
    public function route($request){
       
        //ottento la PCRouterRoute per la richiesta corrente
        $route = $this->getRouteForRequest($request);

        //se la route non esiste lancio eccezione 404
        if($route == NULL){
            $request->setRequestType(PCRequest::TYPE_WEB);
            throw new PCExceptionRoute("Page Not Found",404);
        }
        
        //setto il tipo della richiesta
        $request->setRequestType($route->getType());
        
        //Creo l' auth e la inserisco nella request
        $authClass = $route->getAuthHandlerClassName();
        $auth = new $authClass($request);
        $request->setAuthHandler($auth);
     
        //ottengo il nome del controller ed il percorso al file contenente la classe e ne creo un istanza
        $className = $route->getClassName();
        $path = $this->controllersBaseDirectory.$route->getControllerPath();
        require $path;
        $controller =  new $className();
        
        //prendo il nome dell'azione
        $actionName = $request->dequeuePathComponent();
        //se non è presente eseguo un azione generica
        if($actionName == NULL){
            return $controller->handleRequest($request);
        }
        //se l' azione è presente provo a cercare una PCRouterSoubroute
        $subRoute = $this->getSubrouteForRequest( $route, $actionName);
        
        if($subRoute == null) throw new PCExceptionRoute("Page Not Found",404);
        
        //se la subroute esiste verifico che il metodo http sia supportato e che sia compatibile con in controller
        
        if($subRoute->getHttpMethod() == $request->getHttp_method() && $controller->supportsHTTPMethod($request->getHttp_method())){
            //in caso positivo eseguo la richiesta e restituisco un PCRenderer oppure PCResponse
            $actionName = $subRoute->getControllerAction();
            return $controller->$actionName($request);
        }
        throw new PCExceptionRoute("Route not found",404);
    }
    /**
     * 
     * @param PCRequest $request
     * @return PCRouterRoute 
     */
    private function getRouteForRequest($request){
        $itemTmp = $request->dequeuePathComponent();
        $item = strtolower($itemTmp);
        foreach ($this->typesRegexes as $key => $value) {
            if(preg_match($value, $request->getDomainName()) === 1){
                
                $routes = $this->routes[$key];
                
                //if(strcmp($item, "")==0) $item = "home";
                //else $item = strtolower($item);
               
                return $routes[$item];
            }
        }
    }
    /**
     * returns the correct subroute for this request
     * @param PCRouterRoute $route
     * @param string $actionName 
     * @return PCRouterSubroute the correct subroute for this request
     */
   private function getSubrouteForRequest( $route, $actionName){
       $actionName = strtolower($actionName);
       return $route->getSubroute($actionName);
   }
    
}
