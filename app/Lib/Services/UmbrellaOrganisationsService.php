<?php

App::uses('BaseService', 'Lib/Services');

/**
 * Class UmbrellaOrganisationsService
 *
 *
 */
class UmbrellaOrganisationsService extends BaseService
{
    public function getOrganisations($params)
    {
        $response = $this->Connector->getRequest('/umbrella_organization', $params);
        if ($response === false) {
            return $this->Connector->getLastResponse();
        }

        if (isset($response['data']) && !empty($response['data'])) {
            return $response['data'];
        } else {
            return [];
        }
    }

    public function getOrganisationList() {
        $response = $this->Connector->getRequest('/umbrella_organization', ['mode' => 'list']);
        if ($response === false) {
            return $this->Connector->getLastResponse();
        }

        $data = [];

        foreach ($response as $key => $value) {
            $data[getUUID($value, 'get')] = $value['name'];
        }

        return $data;
    }

    public function addOrganisation($data) {
        $response = $this->Connector->postRequest('/umbrella_organization', [], $data);
        if($response === false){
            return $this->Connector->getLastResponse();
        }

        return $response;
    }

    public function getOrganisation($id) {
        $response = $this->Connector->getRequest('/umbrella_organization/' . $id, []);
        if ($response === false) {
            return $this->Connector->getLastResponse();
        }

        return $response;
    }

    public function updateOrganisation($id, $data) {

        $response = $this->Connector->putRequest('/umbrella_organization/' . $id, [], $data);
        if ($response === false) {
            return $this->Connector->getLastResponse();
        }

        return $response;
    }

    public function deleteOrganisation($id) {
        $response = $this->Connector->deleteRequest('/umbrella_organization/' . $id, []);
        if($response === false){
            return $this->Connector->getLastResponse();
        }

        return $response;
    }
}