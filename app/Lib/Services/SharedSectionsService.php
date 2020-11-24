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


    public function getSharedSectionSchoolLocations($sectionId,$params)
    {

        $params['order'] = ['name' => 'desc'];

        $response = $this->Connector->getRequest('/shared_sections/'.$sectionId, $params);
        if($response === false){
            return $this->Connector->getLastResponse();
        }

        return $response;
    }

    public function getOptionalSharedShoolLocations($sectionId)
    {
        $response = $this->Connector->getRequest('/shared_sections/optional_school_locations/'.$sectionId, []);
        if($response === false){
            return $this->Connector->getLastResponse();
        }

        return $response;
    }

}
