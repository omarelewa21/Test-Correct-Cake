<?php

App::uses('BaseService', 'Lib/Services');

/**
 * Class SchoolsService
 *
 *
 */
class SchoolsService extends BaseService
{
    public function getSchools($params)
    {
        $response = $this->Connector->getRequest('/school', $params);
        if ($response === false) {
            return $this->Connector->getLastResponse();
        }


        if (isset($response['data']) && !empty($response['data'])) {
            return $response['data'];
        } else {
            return [];
        }
    }

    public function getSchoolList()
    {
        $response = $this->Connector->getRequest('/school', ['mode' => 'list']);
        if ($response === false) {
            return $this->Connector->getLastResponse();
        }
        $data = [];

        foreach ($response as $key => $value) {
            $data[getUUID($value, 'get')] = $value['name'];
        }

        return $data;
    }

    public function addSchool($data) {

        if(empty($data['umbrella_organization_id'])) {
            unset($data['umbrella_organization_id']);
        }

        $response = $this->Connector->postRequest('/school', [], $data);

        if($response === false){
            return $this->Connector->getLastResponse();
        }

        return $response;
    }

    public function getSchool($id) {
        $response = $this->Connector->getRequest('/school/' . $id, []);
        if ($response === false) {
            return $this->Connector->getLastResponse();
        }

        return $response;
    }

    public function updateSchool($id, $data) {

        $response = $this->Connector->putRequest('/school/' . $id, [], $data);
        if ($response === false) {
            return $this->Connector->getLastResponse();
        }

        return $response;
    }

    public function deleteSchool($id) {
        $response = $this->Connector->deleteRequest('/school/' . $id, []);
        if($response === false){
            return $this->Connector->getLastResponse();
        }

        return $response;
    }
}