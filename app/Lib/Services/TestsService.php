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

    public function getCitoTests($params)
    {

        $params['order'] = ['id' => 'desc'];

        $response = $this->Connector->getRequest('/cito_test', $params);
        if($response === false){
            return $this->Connector->getLastResponse();
        }

        return $response;
    }

    public function getSharedSectionsTests($params)
    {

        $params['order'] = ['name' => 'desc'];

        $response = $this->Connector->getRequest('/shared_section_test', $params);
        if($response === false){
            return $this->Connector->getLastResponse();
        }

        return $response;
    }

    public function getSharedSectionsTest($test_id)
    {

        $response = $this->Connector->getRequest('/shared_section_test/'.$test_id,[]);
        if($response === false){
            return $this->Connector->getLastResponse();
        }

        return $response;
    }

    public function duplicateSharedSectionsTest($test_id,$params)
    {
        $response = $this->Connector->postRequest('/shared_section_test/'.$test_id, [], $params);
        if($response === false){
            $error = $this->Connector->getLastResponse();
            if ($this->isValidJson($error)) {
                $err = json_decode($error);
                foreach ($err as $k => $e) {
                    if (is_array($e)) {
                        foreach ($e as $a) {
                            $this->addError($a);
                        }
                    } else {
                        $this->addError($e);
                    }
                }
            } else {
                $this->addError($response);
            }
            return false;
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

    public function getCurrentSubjectsForTeacher($mode = 'list') {
        $params = [
            'mode' => $mode,
            'filter' => ['user_current' => AuthComponent::user()['id']],
        ];

        $response = $this->Connector->getRequest('/subject', $params);

        if($response === false){
            return $this->Connector->getLastResponse();
        }

        return $response;
    }

    public function getSubjects($personal = false, $mode = 'list', $without_demo=false, $without_imp=false) {

        if($personal) {
            if(isset($params['filter'])){
                $params['filter']['user_id'] = AuthComponent::user()['id'];
            } else {
                $params['filter'] = [
                    'user_id' => AuthComponent::user()['id']
                ];
            }
        }

        $params['mode'] = $mode;

        if ($without_demo){
            if(isset($params['filter'])){
                $params['filter']['demo'] = 0;
            } else {
                $params['filter'] = ['demo' => 0];
            }
        }

        if ($without_imp){
            if(isset($params['filter'])){
                $params['filter']['imp'] = 0;
            } else {
                $params['filter'] = ['imp' => 0];
            }
        }

        $response = $this->Connector->getRequest('/subject', $params);

        if($response === false){
            return $this->Connector->getLastResponse();
        }

        return $response;
    }

    public function getCitoSubjects($personal = false, $mode = 'list') {

        if($personal) {
            $params['filter'] = [
                'user_id' => AuthComponent::user()['id']
            ];
        }
        $params['mode'] = $mode;

        $response = $this->Connector->getRequest('/cito_subject', $params);

        if($response === false){
            return $this->Connector->getLastResponse();
        }

        return $response;
    }

    public function getMyBaseSubjects() {

        $response = $this->Connector->getRequest('/my_base_subject', []);

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

    public function getCurrentClasses($params = []) {

        $params['mode'] = 'list';
        $params['filter'] = ['current' => true];

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

    public function archive($take_id)
    {
        $response = $this->Connector->putRequest('/test_take/'.$take_id.'/archive', [], []);
        if($response === false){
            return $this->Connector->getLastResponse();
        }

        return $response;
    }

    public function unArchive($take_id)
    {
        $response = $this->Connector->putRequest('/test_take/'.$take_id.'/un-archive', [], []);
        if($response === false){
            return $this->Connector->getLastResponse();
        }

        return $response;
    }

    public function getTestScore($test_id, $params = []) {
        $response = $this->Connector->getRequest('/test_max_score/' . $test_id, $params);
        if($this->Connector->getLastCode() == 403) {
            return false;
        }

        if($response === false){
            return $this->Connector->getLastResponse();
        }

        return $response;
    }

    public function getTestUrlForLaravel($take_id) {
        return $this->Connector->postRequest(sprintf('/test/%s/with_temporary_login', $take_id), [], []);
    }
}
