<?php

App::uses('BaseService', 'Lib/Services');

/**
 * Class TestsService
 *
 *
 */
class TestingService extends BaseService {
    public function handle($flag) {
        $response = $this->Connector->postRequest('/testing/testing', [] ,[
            'flag' => $flag,
        ]);

        return $response;
    }

    public function seleniumToggle($toggle) {
        $response = $this->Connector->postRequest('/testing/selenium', [] ,[
            'toggle' => $toggle,
        ]);

        return $response;
    }

    public function seleniumState() {
        $response = $this->Connector->getRequest('/testing/selenium', []);

        return $response;
    }
}