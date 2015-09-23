<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of PCAjaxUsersController
 *
 * @author paolo
 */
class PCAjaxUsersController extends PCController{
    
    /**
     * 
     * @param PCRequest $request
     */
    public function loginAction($request){
        
        $param = $request->getParams();

        $auth = $request->getAuthHandler();
        
        if ($auth->authorizeLogin()) {

            $ok = array("result" => "OK");
            return new PCRendererJSON($ok);
        } 
        else {
            throw new PCExceptionController("Auth Required", 401);
        }
        
        return NULL;
    }
    
    /**
     * 
     * @param PCRequest $request
     * @return \PCRendererJSON
     */
    public function changePasswordAction($request){
        
        $auth = $request->getAuthHandler();
        
        if($auth->isAuthorized() == false){
            return new PCRendererJSON(array("error"=>"not authorized"), 401);
        }
     
        $user = PCModelUser::getCurrentUser();
        
        $attributes = $request->getParams();
        
        $oldPass = $attributes['oldPass'];
        $newPass = $attributes['newPass'];
        
        if(PCMapperUser::validatePassword($newPass)==FALSE){
            return new PCRendererJSON(array("error"=>"new password is not valid"), 400);
        }
        if(strcmp($oldPass, $newPass) == 0){
            return new PCRendererJSON(array("error"=>"old password and new password are equals"), 400);
        }
        
        $oldPassHash = PCAuth::computeHashForString($oldPass);
        $newPassHash = PCAuth::computeHashForString($newPass);
        
        if(PCMapperUser::changePasswordForUser($user,$newPassHash, $oldPassHash)){
            return new PCRendererJSON(array("result"=>"Password changed!!!"), 200);
        }
        return new PCRendererJSON(array("error"=>"wrong password"), 400);
        
    }
    public function contactAction($request){
        $attributes = $request->getParams();
        
        $name = $attributes['name'];
        $email = $attributes['email'];
        $object = $attributes['object'];
        $text = $attributes['text'];
        $challenge = $attributes['recaptcha_challenge_field'];
        $response = $attributes['recaptcha_response_field'];
        
        if(!isset($name) || !isset($email) || !isset($object) || !isset($text) ){
            return new PCRendererJSON(array("error"=>"Please check fields!!!"), 200);
        }
        
        require_once (__EXTERNAL_LIBRARIES__ . '/recaptcha/recaptchalib.php');
        $privatekey = "6Lfm39cSAAAAAFpyN0tQr4TYNt1zqiaHn9E22lYb";
        $resp = recaptcha_check_answer($privatekey, $_SERVER["REMOTE_ADDR"], $challenge, $response);
        
        if (!$resp->is_valid) {
            // What happens when the CAPTCHA was entered incorrectly
            error_log($resp->error);
            return new PCRendererJSON(array("error" => "Incorrect Captcha"));
        }
        
        $user = PCModelUser::getCurrentUser();
        $attributes['user'] = $user;
        
        $mail = PCEmailBuilder::buildContactUsEmail($attributes);
        PCEmailSender::sendMail($mail);
        
        
        return new PCRendererJSON(array("result" => "Ok"));
    }
    
    /**
     * 
     * @param PCRequest $request
     */
    public function handleRepassAction($request) {
        $error = NULL;
        $user = NULL;
        
        $param = $request->getParams();
        
        $result = PCMapperRepass::createRepassRequest($param['email'], $user, $error);
        
        if ($result == FALSE) {
            return new PCRendererJSON(array("error" => $error));
        }
        

        $mail = NULL;
        $domain = PCConfigManager::sharedManager()->getValue('DOMAIN_NAME');
        $mail = PCEmailBuilder::buildEmailForPasswordLost("http://$domain/page/repass/?id=" . $user->getIdentifier() . "&val=" . $result, $user);
        
        PCEmailSender::sendMail($mail);


        $ok = array("result" => "OK");
        return new PCRendererJSON($ok);
        
    }
    
    /**
     * 
     * @param PCRequest $request
     */
    public function registerAction($request) {
        
        require_once (__EXTERNAL_LIBRARIES__ . '/recaptcha/recaptchalib.php');

        $auth = $request->getAuthHandler();
        if ($auth->isAuthorized()) {
            return new PCRendererJSON(array("error" => "you can't register a new user while logged"), 400);
        }

        $attributes = $request->getParams();

        $privatekey = "6Lfm39cSAAAAAFpyN0tQr4TYNt1zqiaHn9E22lYb";
        $resp = recaptcha_check_answer($privatekey, $_SERVER["REMOTE_ADDR"], $attributes["recaptcha_challenge_field"], $attributes["recaptcha_response_field"]);

        if (!$resp->is_valid) {
            // What happens when the CAPTCHA was entered incorrectly
            error_log($resp->error);
            return new PCRendererJSON(array("captcha_error" => "Incorrect Captcha"));
        }

        if( !isset($attributes['username']) || !isset($attributes['name']) || !isset($attributes['surname']) || !isset($attributes['email']) || !isset($attributes['password'])){
            throw new PCExceptionAuth("Missing param", 400);
        }
        $inputError = NULL;
        
        
        
        
        if(PCHelperValidator::validatePassword($attributes['password'], $inputError) == FALSE){
            return new PCRendererJSON(array("error" => $inputError), 400);
        }
        if(PCHelperValidator::validateUsername($attributes['username'], $inputError) == FALSE){
            return new PCRendererJSON(array("error" => $inputError), 400);
        }
        
        if(PCHelperValidator::validateName($attributes['name'], $inputError) == FALSE){
            return new PCRendererJSON(array("error" => $inputError), 400);
        }
        if(PCHelperValidator::validateSurname($attributes['surname'], $inputError) == FALSE){
            return new PCRendererJSON(array("error" => $inputError), 400);
        }
        if(PCHelperValidator::validateEmail($attributes['email'], $inputError) == FALSE){
            return new PCRendererJSON(array("error" => $inputError), 400);
        }
        
        $username = ($attributes['username']);
        $name = ($attributes['name']);
        $surname = ($attributes['surname']);
        $email = ($attributes['email']);
        $password = ($attributes['password']);


        $store = array();
        $store['username'] = $username;
        $store['name'] = $name;
        $store['surname'] = $surname;
        $store['email'] = $email;
        $store['password'] = PCAuth::computeHashForString($password);
        
        $error = NULL;
        
        if( PCMapperUser::createUserWithAttributes($store, $error) ){
            
            if(PCConfigManager::sharedManager()->getBoolValue('NOTIF_ON_REGISTER')){
                PCHelperNotificationSender::sendPushNotificationToAdmin("User Registered", "uname: $username Name: $name Sur: $surname mail: $email");
            }
            
            
            return new PCRendererJSON(array("OK" => "User added"));
        }

        return new PCRendererJSON(array("error" => $error), 400);
         
    }
    
    

    public function supportsHTTPMethod($method) {
        if($method == PCRequest::HTTP_METHOD_GET || $method == PCRequest::HTTP_METHOD_POST){
            return TRUE;
        }
        return parent::supportsHTTPMethod($method);
    }
    
    
    
}

