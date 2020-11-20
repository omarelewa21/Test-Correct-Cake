<?php

App::uses('BaseService', 'Lib/Services');

/**
 * Class TestsService
 *
 *
 */
class SharedSectionsService extends BaseService {


    public function add($test) {

        $response = $this->Connector->postRequest('/test', [], $test);

        if($this->Connector->getLastCode() == 422) {
            return 'unique_name';
        }

        if($response === false){
            return $this->Connector->getLastResponse();
        }

        return $response;
    }


    public function getSharedSections($params)
    {

        $params['order'] = ['id' => 'desc'];

        $response = $this->Connector->getRequest('/shared_sections', $params);
        if($response === false){
            return $this->Connector->getLastResponse();
        }

        return $response;
    }

}
