<?

/**
 * Description of PCAuthCookies
 *
 * @author paolo
 */
require_once (__EXTERNAL_LIBRARIES__ . '/aws/aws.phar');

use Aws\DynamoDb\DynamoDbClient;
use Aws\Common\Enum\Region;


class PCAuthCookies extends PCAuth {
    
    /**
     *
     * @var PCAuthCookiesAdapter 
     */
    protected $adapter;

    /**
     * 
     * @param PCRequest $request
     * @param PCAuthCookiesAdapter $adapter
     */
    public function __construct($request, $adapter = NULL) {
        parent::__construct($request);
        
        $this->adapter = ($adapter == NULL ? new PCAuthDefaultCookiesAdapter() : $adapter);
        $this->application = PCModelManager::fetchObjectWithIdentifier(PCModelApplication::getMapper(), PCModelApplication::WEBSITE_APP_ID, NULL, TRUE);
        
        $this->setupSession();
        $this->authorize();
    }

    public function authorize() {
        if($this->auth_state == static::PCAuthUnknownState){
            $user = NULL;
            $result = (bool) $this->adapter->autorizeWithCookies($this->request->getCookies(), $this->application, $user);
            $this->userIdentifier = $user;
            $this->auth_state = ($result ? static::PCAuthAllowedState : static::PCAuthDeniedState );
            return $result;
        }
        else if($this->auth_state == static::PCAuthAllowedState){
            return TRUE;
        }
        else{
            throw new PCExceptionAuth("Internal Inconsistency",500);
        }
    }

    public function authorizeLogin() {
        if($this->auth_state == static::PCAuthUnknownState || $this->auth_state == static::PCAuthDeniedState){
             $result = $this->adapter->doLogin($this->request, $this->application);
            $this->auth_state = ($result ? static::PCAuthAllowedState : static::PCAuthDeniedState );
            return $result;
        }
        else{
            throw new PCExceptionAuth("Internal Inconsistency",500);
        }
      
    }

    public function logout() {
        return $this->adapter->doLogout($this->application);
    }
    
    
    public function setupSession() {
        
        if( defined('DEBUG') ){
            session_start();
            return;
        }
        $client = DynamoDbClient::factory(array(
                    'key' => 'AKIAIDBPOWSJGOKXMFXQ',
                    'secret' => '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',
                    'region' => Region::EU_WEST_1
                ));



        $sessionHandler = $client->registerSessionHandler(
                array('table_name' => 'session_table',
                    'hash_key' => 'id',
                    'lifetime' => 86400,
                    'session_locking' => false));
        
        
        
        session_start();
    }
    
    
    
    
}