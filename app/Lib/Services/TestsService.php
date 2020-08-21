<?php

App::uses('BaseService', 'Lib/Services');
App::uses('QuestionsService', 'Lib/Services');

/**
 * Class TestsService
 *
 *
 */
class TestsService extends BaseService {

    public function duplicate($test_id) {
        $test             = $this->getTest($test_id);
        $questions        = $this->getQuestions($test_id);
        $QuestionsService = new QuestionsService();

        $test = array(
            'status' => 0
        );

        $response = $this->Connector->postRequest('/test/' . $test_id . '/duplicate', [], $test);

        if($response === false){
            return $this->Connector->getLastResponse();
        }
        
        return $response;
    }

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

    public function edit($test_id, $test) {

        $response = $this->Connector->putRequest('/test/' . $test_id, [], $test);

        if($this->Connector->getLastCode() == 422) {
            return 'unique_name';
        }

        if($response === false){
            return $this->Connector->getLastResponse();
        }

        return $response;
    }

    public function getTests($params)
    {

        $params['order'] = ['id' => 'desc'];

        $response = $this->Connector->getRequest('/test', $params);
        if($response === false){
            return $this->Connector->getLastResponse();
        }

        return $response;
    }

    public function getTest($test_id) {
        $response = $this->Connector->getRequest('/test/' . $test_id, []);
        if($response === false){
            return $this->Connector->getLastResponse();
        }

        return $response;
    }

    public function deleteTest($test_id) {
        $response = $this->Connector->deleteRequest('/test/' . $test_id, []);


        if($response === false){
            return $this->Connector->getLastResponse();
        }

        return $response;
    }

    public function getQuestions($test_id) {

        $params = [
                'filter' => [
                    'test_id' => $this->getTest($test_id)['id']
                ],
                'mode' => 'all',
                'order' => [
                    'order' => 'asc'
                ]
            ]
        ;
        $response = $this->Connector->getRequest('/test_question', $params);

        if($response === false){
            return $this->Connector->getLastResponse();
        }

        return $response;
    }

    public function getQuestionGroups($test_id) {
        $response = $this->Connector->getRequest('/test/' . $test_id . '/question_group', []);
        if($response === false){
            return $this->Connector->getLastResponse();
        }

        return $response;
    }

    public function getEducationLevels($small = true, $personal = true) {

        if($personal) {
            $params['filter'] = [
                'user_id' => AuthComponent::user()['id']
            ];
        }
        $params['mode'] = 'list';

        $response = $this->Connector->getRequest('/education_level', $params);
        if($response === false){
            return $this->Connector->getLastResponse();
        }

        if($small) {
            $newArray = [];
            foreach($response as $item) {
                $newArray[$item['id']] = $item['name'];
            }

            return $newArray;
        }

        return $response;
    }

    public function getPeriods($full = false, $params = null) {

        if(empty($params)) {
            $params = ['mode' => 'list'];
        }else{
            $params['mode'] = 'list';
        }

        $periods = $this->Connector->getRequest('/period', $params);
        if($periods === false){
            return $this->Connector->getLastResponse();
        }

        if(!$full) {
            $newArray = [];

            foreach ($periods as $period) {
                $newArray[$period['id']] = $period['name'];
            }

            return $newArray;
        }else{
            return $periods;
        }
    }

    public function getPeriod($id) {
        $periods = $this->Connector->getRequest('/period/' . $id, []);
        if($periods === false){
            return $this->Connector->getLastResponse();
        }

        return $periods;
    }

    public function getSubjects($personal = false, $mode = 'list') {

        if($personal) {
            $params['filter'] = [
                'user_id' => AuthComponent::user()['id']
            ];
        }
        $params['mode'] = $mode;

        $response = $this->Connector->getRequest('/subject', $params);

        if($response === false){
            return $this->Connector->getLastResponse();
        }

        return $response;
    }

    public function getInvigilators() {
        $response = $this->Connector->getRequest('/invigilator/list', []);
        if($response === false){
            return $this->Connector->getLastResponse();
        }

        return $response;
    }

    public function getClasses($params = []) {

        $params['mode'] = 'list';

        $response = $this->Connector->getRequest('/school_class', $params);
        if($response === false){
            return $this->Connector->getLastResponse();
        }

        return $response;
    }

    public function getClassesItems($params) {
        $response = $this->Connector->getRequest('/school_class', $params);
        if($response === false){
            return $this->Connector->getLastResponse();
        }

        return $response;
    }

    public function getKinds() {
        $response = $this->Connector->getRequest('/test_kind/list', []);
        if($response === false){
            return $this->Connector->getLastResponse();
        }

        return $response;
    }
}