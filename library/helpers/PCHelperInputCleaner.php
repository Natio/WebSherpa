<?
require_once ( __EXTERNAL_LIBRARIES__ . '/htmlpurifier/library/HTMLPurifier.auto.php');

/**
 * Classe helper per filtrare l' input dell' utente ed evitare XSS
 *
 * @author paolo
 */
class PCHelperInputCleaner {
    
    /**
     * the static purifier
     * @var HTMLPurifier 
     */
    private static $purifier;
    
    /**
     * Resituisce il "purificatore"
     * @return HTMLPurifier 
     */
    private static function getPurifier(){
        if(static::$purifier == null){
            $config  = HTMLPurifier_Config::createDefault();
            $config->set('Cache.DefinitionImpl', null);
            static::$purifier = new HTMLPurifier($config);
        }
        return static::$purifier;
    }



    /**
     * Pulisce la stringa passata in input
     * @param string $input
     * @return string 
     */
    public static function cleanInputString($input){
        $pur = static::getPurifier();
        return $pur->purify($input);
    }
}