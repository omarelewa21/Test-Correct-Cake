<?php

App::uses('BaseService', 'Lib/Services');

/**
 * Class AuthService
 *
 * This service will handle all authentication to the TComm Core.
 */
class AuthService extends BaseService {

    public function setUser($key)
    {
        $this->Connector->setUser($key);
    }

    public function setApiKey($key)
    {
        $this->Connector->setApiKey($key);
    }

    public function setSessionHash($hash) {
        $this->Connector->setSessionHash($hash);
    }
}