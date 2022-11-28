<?php

App::uses('BaseAuthenticate', 'Controller/Component/Auth');
App::uses('CoreConnector', 'Lib');

class TCCoreAuthenticate extends BaseAuthenticate {

    public $error;

    public function authenticate(CakeRequest $request, CakeResponse $response) {
        $captcha = false;
        if(empty($request->data['User']['email']) || empty($request->data['User']['password'])){
            return false;
        }

        if(isset($request->data['User']['captcha_string'])){
            $captcha = true;
        }
        $connector = CoreConnector::instance();
        $response = $connector->fetchKeys($request->data['User']['email'], $request->data['User']['password'], $captcha, $request->clientIp());
        if(!$response){
            $this->error = $connector->getLastResponse();
            return false;
        } else {
            return $response;
        }
    }
}