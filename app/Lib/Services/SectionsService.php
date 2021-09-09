<?php

App::uses('BaseService', 'Lib/Services');

/**
 * Class SectionsService
 *
 *
 */
class SectionsService extends BaseService
{
    public function getSections($params)
    {
        $response = $this->Connector->getRequest('/section', $params);
        if ($response === false) {
            return $this->Connector->getLastResponse();
        }


        if (isset($response) && !empty($response)) {
            return $response;
        } else {
            return [];
        }
    }

    public function getSectionList()
    {
        $response = $this->Connector->getRequest('/section', ['mode' => 'list']);
        if ($response === false) {
            return $this->Connector->getLastResponse();
        }

        return $response;
    }

    public function addSection($data) {
        $response = $this->Connector->postRequest('/section', [], $data);

        if($response === false){
            return $this->Connector->getLastResponse();
        }

        return $response;
    }

    public function addSectionSubject($section_id, $data) {
        $data['section_id'] = $this->getSection($section_id)['id'];
        $response = $this->Connector->postRequest('/subject', [], $data);

        if($response === false){
            return $this->Connector->getLastResponse();
        }

        return $response;
    }

    public function getSection($id) {
        $response = $this->Connector->getRequest('/section/' . $id, []);
        if ($response === false) {
            return $this->Connector->getLastResponse();
        }

        return $response;
    }

    public function getSectionSubject($id) {
        $response = $this->Connector->getRequest('/subject/' . $id, []);
        if ($response === false) {
            return $this->Connector->getLastResponse();
        }

        return $response;
    }

    public function getBaseSubjects() {
        $response = $this->Connector->getRequest('/base_subject', ['mode' => 'list']);
        if ($response === false) {
            return $this->Connector->getLastResponse();
        }

        return $response;
    }

    public function updateSection($id, $data) {

        $response = $this->Connector->putRequest('/section/' . $id, [], $data);
        if ($response === false) {
            return $this->Connector->getLastResponse();
        }

        return $response;
    }

    public function getSectionSubjectList() {
        $response = $this->Connector->getRequest('/subject', ['mode' => 'list']);
        if ($response === false) {
            return $this->Connector->getLastResponse();
        }

        return $response;
    }

    public function updateSectionSubject($id, $data) {

        $response = $this->Connector->putRequest('/subject/' . $id, [], $data);
        if ($response === false) {
            return $this->Connector->getLastResponse();
        }

        return $response;
    }

    public function deleteSection($id) {
        $response = $this->Connector->deleteRequest('/section/' . $id, []);
        if($response === false){
            return $this->Connector->getLastResponse();
        }

        return $response;
    }

    public function deleteSectionSubject($id) {
        $response = $this->Connector->deleteRequest('/subject/' . $id, []);
        if($response === false){
            return $this->Connector->getLastResponse();
        }

        return $response;
    }
}