<?php

App::uses('BaseService', 'Lib/Services');

/**
 * Class DemoService
 *
 *
 */
class DemoService extends BaseService {

    public function getTests()
    {
        $response = $this->Connector->getRequest('/test', array());
        if($response === false){
            return $this->Connector->getLastResponse();
        }

        return $response;
    }
}