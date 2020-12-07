<?php

App::uses('BaseService', 'Lib/Services');

/**
 * Class TestsService
 *
 *
 */
class SharedSectionsService extends BaseService {


    public function delete($sectionId,$schoolLocactionId)
    {
        $response = $this->Connector->deleteRequest(sprintf('/shared_sections/%s/%s',$sectionId, $schoolLocactionId), []);

        if($this->Connector->getLastCode() == 422) {
            return 'Er is iets fout gegaan, probeer het nogmaals';
        }

        if($response === false){
            return $this->Connector->getLastResponse();
        }

        return $response;
    }

    public function add($sectionId,$data) {

        $response = $this->Connector->postRequest('/shared_sections/'.$sectionId, [], $data);

        if($this->Connector->getLastCode() == 422) {
            return 'Er is iets fout gegaan, probeer het nogmaals';
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
