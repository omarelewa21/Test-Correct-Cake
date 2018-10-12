<?php

App::uses('BaseService', 'Lib/Services');

/**
 * Class SchoolLocationsService
 *
 *
 */
class SchoolLocationsService extends BaseService
{
    public function getSchoolLocations($params)
    {
        $response = $this->Connector->getRequest('/school_location', $params);
        if ($response === false) {
            return $this->Connector->getLastResponse();
        }


        if (isset($response['data']) && !empty($response['data'])) {
            return $response['data'];
        } else {
            return [];
        }
    }

    public function getIps($location_id) {
        $response = $this->Connector->getRequest('/school_location/' . $location_id . '/school_location_ip', [
            'mode' => 'all'
        ]);
        if ($response === false) {
            return $this->Connector->getLastResponse();
        }


        return $response;
    }

    public function addIp($location_id, $data) {

        $response = $this->Connector->postRequest('/school_location/' . $location_id . '/school_location_ip', [], $data);

        if ($response === false) {
            return $this->Connector->getLastResponse();
        }


        return $response;
    }

    public function deleteIp($location_id, $ip_id) {
        $response = $this->Connector->deleteRequest('/school_location/' . $location_id . '/school_location_ip/' . $ip_id, []);

        if ($response === false) {
            return $this->Connector->getLastResponse();
        }


        return $response;
    }

    public function getSchoolLocationList()
    {
        $response = $this->Connector->getRequest('/school_location', ['mode' => 'list']);
        if ($response === false) {
            return $this->Connector->getLastResponse();
        }


        return $response;
    }

    public function addSchoolLocation($data) {

        if(empty($data['school_id'])) {
            unset($data['school_id']);
        }

        $response = $this->Connector->postRequest('/school_location', [], $data);

        if($response === false){
            return $this->Connector->getLastResponse();
        }

        return $response;
    }

    public function getSchoolLocation($id) {
        $response = $this->Connector->getRequest('/school_location/' . $id, []);
        if ($response === false) {
            return $this->Connector->getLastResponse();
        }

        return $response;
    }

    public function getGradingScales() {
        $response = $this->Connector->getRequest('/grading_scale', ['mode' => 'list']);
        if ($response === false) {
            return $this->Connector->getLastResponse();
        }

        return $response;
    }

    public function updateSchoolLocation($id, $data) {

        $response = $this->Connector->putRequest('/school_location/' . $id, [], $data);
        if ($response === false) {
            return $this->Connector->getLastResponse();
        }

        return $response;
    }

    public function deleteSchoolLocation($id) {
        $response = $this->Connector->deleteRequest('/school_location/' . $id, []);
        if($response === false){
            return $this->Connector->getLastResponse();
        }

        return $response;
    }

    public function deleteLicence($location_id, $licence_id) {

        $response = $this->Connector->deleteRequest('/school_location/' . $location_id . '/license/' . $licence_id, []);
        if($response === false){
            return $this->Connector->getLastResponse();
        }

        return $response;
    }

    public function addLicence($data, $location_id) {

        $response = $this->Connector->postRequest('/school_location/' . $location_id . '/license', [], $data);

        if($response === false){
            return $this->Connector->getLastResponse();
        }

        return $response;
    }

    public function getLicence($location_id, $licence_id) {

        $response = $this->Connector->getRequest('/school_location/' . $location_id . '/license/' . $licence_id, []);

        if($response === false){
            return $this->Connector->getLastResponse();
        }

        return $response;
    }

    public function updateLicence($location_id, $licence_id, $data) {

        $response = $this->Connector->putRequest('/school_location/' . $location_id . '/license/' . $licence_id, [], $data);


        if($response === false){
            return $this->Connector->getLastResponse();
        }

        return $response;
    }
}