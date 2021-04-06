<?php

App::uses('BaseAuthenticate', 'Controller/Component/Auth');
App::uses('CoreConnector', 'Lib');

class TCCoreAuthenticate extends BaseAuthenticate {

    public function authenticate(CakeRequest $request, CakeResponse $response) {
        $captcha = false;
        if(empty($request->data['User']['email']) || empty($request->data['User']['password'])){
            return false;
        }

        if(isset($request->data['User']['captcha_string'])){
            $captcha = true;
        }

        $response = CoreConnector::instance()->fetchKeys($request->data['User']['email'], $request->data['User']['password'], $captcha, $request->clientIp());
        if(!$response){
            return false;
        } else {
            return $response;
        }
    }
}