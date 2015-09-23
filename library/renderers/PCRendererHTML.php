<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of PCRendererHTML
 *
 * @author paolo
 */
class PCRendererHTML extends PCRenderer {

    /**
     * L' array contenente i nomi degli script da collegare
     * @var array 
     */
    protected $scripts;

    /**
     * L' array contenente i nomi dei fogli di stile da collegare
     * @var array 
     */
    protected $styleSheets;

    /**
     * Il nome del template
     * @var string 
     */
    protected $templateName;

    /**
     * Le pagine da includere
     * @var array 
     */
    protected $pages;
    
    /**
     * 
     * @param string $view
     * @param array $result
     * @return PCRendererHTML
     */
    public static function rendererForView($view, $result){
        $template_name = "fresh";
        $configuration_file_path =  __ROOT__ . "/templates/".$template_name."/config.php";
        
        $config = require $configuration_file_path;
        
        $css = $config['CSS'];
        $scripts = $config['scripts'];
        
        $fileConfig = $config[$view];
        
        $css = array_merge($css,$fileConfig['CSS']);
        $scripts = array_merge($scripts, $fileConfig['scripts']);
        
        $pages = $fileConfig["pageOrder"];
        
        return new PCRendererHTML($result, $template_name, $pages, $scripts, $css);
    }

    function __construct($result, $templateName, $pages, $scripts = NULL, $styleSheets = NULL) {
        parent::__construct($result);
        
        $user = PCModelUser::getCurrentUser();
        
        if(isset($user)){
            $this->result['user'] = $user;
        }
        $this->scripts = ($scripts == NULL ? array() : $scripts);
        $this->styleSheets = ($styleSheets == NULL ? array() : $styleSheets);
        $this->templateName = $templateName;
        $this->pages = $pages;
    }
    /**
     * 
     * @param string $script
     */
    public function addScript($script) {
        $this->scripts[] = $script;
    }

    /**
     * Adds a stylesheet
     * @param string $sheet
     */
    public function addStyleSheet($sheet) {
        $this->styleSheets[] = $sheet;
    }

    public function render() {

        foreach ($this->pages as $p) {
            require __ROOT__ . "/templates/" . $this->templateName . "/" . $p;
        }
    }

    protected function addAnalytics() {
        if (!defined('DEBUG')) {
            require __ROOT__ . "/templates/" . $this->templateName . "/analyticsTracking.php";
        }
    }

    protected function linkStyleSheets() {
        foreach ($this->scripts as $script) {
            echo "<script type=\"text/javascript\" src=\"/public/" . $this->templateName . "/js/" . $script . "\"></script>";
        }
    }

    protected function linkStyleScripts() {
        foreach ($this->styleSheets as $styleSheet) {
            echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"/public/" . $this->templateName . "/css/" . $styleSheet . "\"/>";
        }
    }

    /**
     * Restituisce il titolo della pagina
     * @return string
     */
    protected function getPageTitle() {
        if (isset($this->result['title'])) {
            return $this->result['title'];
        }
        return "No title";
    }

    /**
     * Restituisce il percorso assoluto alla cartella delle immagini dello specifico tempplate
     * @return string
     */
    protected function getImageDirectoryPath() {
        return "/public/" . $this->templateName . "/img/";
    }

    /**
     * Restituisce il percorso all'immagine per il template corrente
     * @param string $image
     * @return string
     */
    protected function getPathForImage($image) {
        return $this->getImageDirectoryPath() . $image;
    }
    
    /**
     * Restituisce l' utente attualmente connesso
     * @return PCModelUser
     */
    protected function getUser(){
        if(isset($this->result['user'])){
            return $this->result['user'];
        }
        
        return NULL;
    }
    
    /**
     * Restituisce TRUE se c'è un utente collegato
     * @return bool
     */
    protected function hasLoggedUser(){
        return isset($this->result['user']);
    }

    
    /**
     * Restituisce l'HTML del captcha
     * @return string
     */
    protected function getCaptcha(){
        
        require_once (__EXTERNAL_LIBRARIES__ . '/recaptcha/recaptchalib.php');
        
        $publicKey = "6Lfm39cSAAAAAGVSikvIroNS4TTMC0hVbALx6BDz";
        
        $html = recaptcha_get_html($publicKey, NULL, TRUE);
        return $html;
         
        
    }
}

