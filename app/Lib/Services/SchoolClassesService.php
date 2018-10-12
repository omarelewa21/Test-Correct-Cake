<?php

App::uses('BaseService', 'Lib/Services');

/**
 * Class SchoolClassesService
 *
 *
 */
class SchoolClassesService extends BaseService {
    public function getClasses($params) {
        $response = $this->Connector->getRequest('/school_class', $params);
        if ($response === false) {
            return $this->Connector->getLastResponse();
        }


        if (isset($response['data']) && !empty($response['data'])) {
            return $response['data'];
        } else {
            return [];
        }
    }

    public function addClass($data) {
        $response = $this->Connector->postRequest('/school_class', [], $data);

        if($response === false){
            return $this->Connector->getLastResponse();
        }

        return $response;
    }

    public function getClassesList($params = []) {

        $params['mode'] = 'list';

        $response = $this->Connector->getRequest('/school_class', $params);
        if ($response === false) {
            return $this->Connector->getLastResponse();
        }


        return $response;
    }

    public function getForLocationId($location_id) {
        $response = $this->Connector->getRequest('/school_class', ['mode' => 'list', 'filter' => ['school_location_id' => $location_id]]);
        if ($response === false) {
            return $this->Connector->getLastResponse();
        }


        return $response;
    }

    public function updateClass($class_id, $data) {
        $response = $this->Connector->putRequest('/school_class/' . $class_id, [], $data);
        if ($response === false) {
            return $this->Connector->getLastResponse();
        }

        return $response;
    }

    public function getClass($class_id, $params = []) {
        $response = $this->Connector->getRequest('/school_class/' . $class_id, $params);
        if ($response === false) {
            return $this->Connector->getLastResponse();
        }


        return $response;
    }

    public function addManager($class_id, $manager_id ){
        $response = $this->Connector->putRequest('/user/' . $manager_id, [], [
            'add_manager_school_class' => $class_id
        ]);

        if($response === false){
            return $this->Connector->getLastResponse();
        }

        return $response;
    }

    public function addMentor($class_id, $mentor_id ){
        $response = $this->Connector->putRequest('/user/' . $mentor_id, [], [
            'add_mentor_school_class' => $class_id
        ]);

        if($response === false){
            return $this->Connector->getLastResponse();
        }

        return $response;
    }

    public function removeFromClass($user_id, $params ){
        $response = $this->Connector->putRequest('/user/' . $user_id, [], $params);

        if($response === false){
            return $this->Connector->getLastResponse();
        }

        return $response;
    }

    public function addStudent($class_id, $student_id ){
        $response = $this->Connector->putRequest('/user/' . $student_id, [], [
            'add_student_school_class' => $class_id
        ]);

        if($response === false){
            return $this->Connector->getLastResponse();
        }

        return $response;
    }

    public function removeTeacher($teacher_id) {


        $response = $this->Connector->deleteRequest('/teacher/' . $teacher_id, []);

        if($response === false){
            return $this->Connector->getLastResponse();
        }

        return $response;
    }

    public function addTeacher($class_id, $data) {

        $response = $this->Connector->postRequest('/teacher', [], [
            'class_id' => $class_id,
            'user_id' => $data['teacher_id'],
            'subject_id' => $data['subject_id']
        ]);

        if($response === false){
            return $this->Connector->getLastResponse();
        }

        return $response;
    }

    public function deleteClass($class_id) {
        $response = $this->Connector->deleteRequest('/school_class/' . $class_id, []);


        if($response === false){
            return $this->Connector->getLastResponse();
        }

        return $response;
    }
}