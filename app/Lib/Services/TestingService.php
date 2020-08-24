<?php

App::uses('BaseService', 'Lib/Services');

/**
 * Class TestsService
 *
 *
 */
class TestingService extends BaseService {
    public function handle($flag) {
        $response = $this->Connector->postRequest('/testing', [] ,[
            'flag' => $flag,
        ]);

        return $response;
    }

    public function seleniumToggle($toggle) {
        $response = $this->Connector->postRequest('/selenium', [] ,[
            'toggle' => $toggle,
        ]);

        if ($toggle == 'true') {
            $this->handle('testdb');
        }

        return $response;
    }

    public function seleniumState() {
        $response = $this->Connector->getRequest('/selenium', []);

        return $response;
    }
}