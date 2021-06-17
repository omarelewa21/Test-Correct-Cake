<?php

App::uses('BaseService', 'Lib/Services');

/**
 * Class SchoolClassesService
 *
 *
 */
class SchoolClassesService extends BaseService {

    /**
     *
     * get the classes for the loggedin teacher
     */
    public function getMyClasses()
    {
        return $this->getClasses([
            'current_school_year' => true,
            'mode' => 'all'
        ]);
    }

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

    public function doImportStudents($location_id,$class_id,$data){
        $response = $this->Connector->postRequest('/school_class/importStudents/'.$location_id.'/'.$class_id, [], $data);

        if ($response === false) {
            $this->handleFalseResponse($response);
            return false;
        }
        return $response;
    }

    public function doImportStudentsWithClasses($location_id,$data)
    {
        $response = $this->Connector->postRequest('/school_class/importStudentsWithClasses/'.$location_id, [], $data);

        if ($response === false) {
            $error = $this->Connector->getLastResponse();
            if ($this->isValidJson($error)) {
                $err = json_decode($error);
                foreach ($err->errors as $k => $e) {
                    if (is_array($e)) {
                        foreach ($e as $b => $a) {
                            $this->addAssocError($k, $a);
                        }
                    } else {
                        $this->addAssocError($k, $e);
                    }
                }
            } else {
                $this->addError($response);
            }
            return false;
        }
        return $response;
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

    public function getForLocationId($location_id,$mode = 'list') {
        $allowedModes = ['all','list','uuidlist','paginate'];
        if(!in_array($mode,$allowedModes)){
            throw new Exception(('mode not allowed in '.__CLASS__.' on line '.__LINE__));
        }
        $response = $this->Connector->getRequest('/school_class', ['mode' => $mode, 'filter' => ['school_location_id' => $location_id]]);
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

    public function updateWithEductionLevelsForMainClasses($data) {

        $response = $this->Connector->putRequest('/school_class/update_with_education_levels_for_main_classes',[], $data);

        if ($response === false) {
            $this->handleFalseResponse($response);
            return false;
        }
        return $response;
    }
    public function updateWithEductionLevelsForClusterClasses($data) {
        $response = $this->Connector->putRequest('/school_class/update_with_education_levels_for_cluster_classes',[], $data);

        if ($response === false) {
            $this->handleFalseResponse($response);
            return false;
        }

        return $response;
    }


    public function updateTeachersWithSubjectsForClusterClasses($data) {
        $response = $this->Connector->putRequest('/teacher/update_with_subjects_for_cluster_classes',[], $data);

        if ($response === false) {
            $this->handleFalseResponse($response);
            return false;
        }
        return $response;
    }



    protected function handleFalseResponse($response)
    {
        $error = $this->Connector->getLastResponse();
        if ($this->isValidJson($error)) {
            $err = json_decode($error);
            foreach ($err->errors as $k => $e) {
                if (is_array($e)) {
                    foreach ($e as $b => $a) {
                        $this->addAssocError($k, $a);
                    }
                } else {
                    $this->addAssocError($k, $e);
                }
            }
        } else {
            $this->addError($response);
        }
    }
}
