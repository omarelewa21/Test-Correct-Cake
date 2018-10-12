<?php

App::uses('BaseService', 'Lib/Services');

/**
 * Class ContactsService
 *
 *
 */
class ContactsService extends BaseService {
    public function getContacts($params) {

        $response = $this->Connector->getRequest('/contact', $params);
        if($response === false){
            return $this->Connector->getLastResponse();
        }

        return $response;
    }

    public function addContact($data) {


        $response = $this->Connector->postRequest('/contact', [], $data);

        if($response === false){
            return $this->Connector->getLastResponse();
        }

        return $response;
    }

    public function connectTo($contact, $owner, $owner_id, $type) {

        $data = ['add_' . $type . '_contact' => $contact['id']];

        $response = $this->Connector->putRequest('/' . $owner . '/' . $owner_id, [], $data);

        if($response === false){
            return $this->Connector->getLastResponse();
        }

        return $response;
    }

    public function deleteContact($owner, $owner_id, $type, $contact_id) {

        $data = ['delete_' . $type . '_contact' => $contact_id];

        $response = $this->Connector->putRequest('/' . $owner . '/' . $owner_id, [], $data);

        if($response === false){
            return $this->Connector->getLastResponse();
        }

        return $response;
    }

    public function getContact($contact_id) {
        $response = $this->Connector->getRequest('/contact/' . $contact_id, []);
        if($response === false){
            return $this->Connector->getLastResponse();
        }

        return $response;
    }

    public function updateContact($contact_id, $data) {
        $response = $this->Connector->putRequest('/contact/' . $contact_id, [], $data);

        if($response === false){
            return $this->Connector->getLastResponse();
        }

        return $response;
    }
}