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
}