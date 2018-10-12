<?php

App::uses('BaseAuthenticate', 'Controller/Component/Auth');
App::uses('CoreConnector', 'Lib');

class TCCoreAuthenticate extends BaseAuthenticate {

    public function authenticate(CakeRequest $request, CakeResponse $response) {
        if(empty($request->data['User']['email']) || empty($request->data['User']['password'])){
            return false;
        }

        $response = CoreConnector::instance()->fetchKeys($request->data['User']['email'], $request->data['User']['password']);
        if(!$response){
            return false;
        } else {
            return $response;
        }
    }
}