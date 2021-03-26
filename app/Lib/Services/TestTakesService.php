<?php

App::uses('BaseService', 'Lib/Services');
App::uses('CakeLog', 'Log');

/**
 * Class TestTakesService
 *
 *
 */
class TestTakesService extends BaseService {

    public function getTestTakeUrlForLaravel($take_id) {
        return $this->Connector->postRequest(sprintf('/test_take/%s/with_temporary_login', $take_id), [], []);
    }

    public function getAttainmentAnalysis($test_take_id) {
        $response = $this->Connector->getRequest(sprintf('/test_take/%s/attainment/analysis',$test_take_id), []);

        if($response === false){
            return $this->Connector->getLastResponse();
        }

        return $response;
    }

    public function getAttainmentAnalysisPerAttainment($test_take_id,$attainment_id) {
        $response = $this->Connector->getRequest(sprintf('/test_take/%s/attainment/%s/analysis',$test_take_id,$attainment_id), []);

        if($response === false){
            return $this->Connector->getLastResponse();
        }

        return $response;
    }

    public function add($test_take) {
        $test_take['school_classes'] = [$test_take['class_id']];

        $response = $this->Connector->postRequest('/test_take', [], $test_take);

        if($response === false){
            return $this->Connector->getLastResponse();
        }

        return $response;
    }

    public function addRetake($test_retake) {

        $test_retake['test_take_status_id'] = 1;
        $response = $this->Connector->postRequest('/test_take', [], $test_retake);

        if($response === false){
            return $this->Connector->getLastResponse();
        }

        return $response;
    }

    public function getTestTakeInfo($take_id) {

        $params['with'] = ['participantStatus'];

        $response = $this->Connector->getRequest('/test_take/' . $take_id, $params);

        if($response === false){
            return $this->Connector->getLastResponse();
        }

        return $response;
    }

    public function getEvents($take_id, $participant_id, $not_confirmed = true) {
        $params['filter'] = [
            'test_participant_id' => $this->getParticipant($take_id, $participant_id)['id']
        ];
        $params['order'] = ['id' => 'desc'];

        if($not_confirmed) {
            $params['filter']['confirmed'] = 0;
        }

        $params['mode'] = 'all';

        $response = $this->Connector->getRequest('/test_take/' . $take_id . '/test_take_event', $params);

        if($response === false){
            return $this->Connector->getLastResponse();
        }

        return $response;
    }

    public function deleteTestTake($take_id)  {
        $response = $this->Connector->deleteRequest('/test_take/' . $take_id, []);

        if($response === false){
            return $this->Connector->getLastResponse();
        }

        return $response;
    }

    public function updateStatus($take_id, $status) {

        $params['test_take_status_id'] = $status;

        $response = $this->Connector->putRequest('/test_take/' . $take_id , $params, []);

        if($response === false){
            return $this->Connector->getLastResponse();
        }

        return $response;
    }

    public function closeNonDispensation($take_id) {

        $params['time_dispensation'] = true;

        $response = $this->Connector->putRequest('/test_take/' . $take_id , $params, []);

        if($response === false){
            return $this->Connector->getLastResponse();
        }

        return $response;
    }


    public function updateParticipantStatus($take_id, $participant_id, $status) {

        $params['test_take_status_id'] = $status;

        echo '/test_take/' . $take_id . '/test_participant/' . $participant_id;

        $response = $this->Connector->putRequest('/test_take/' . $take_id . '/test_participant/' . $participant_id, $params, []);

        echo $this->Connector->getLastResponse();

        if($response === false){
            return $this->Connector->getLastResponse();
        }

        return $response;
    }

    public function startTest($take_id) {

        $test_take['test_take_status_id'] = 3;

        $response = $this->Connector->putRequest('/test_take/' . $take_id, [], $test_take);

        if($response === false){
            return $this->Connector->getLastResponse();
        }

        return $response;
    }

    public function startDiscussion($take_id, $type = 'ALL') {

        $test_take['test_take_status_id'] = 7;
        $test_take['discussion_type'] = $type;

        $response = $this->Connector->putRequest('/test_take/' . $take_id, [], $test_take);

        if($response === false){
            return $this->Connector->getLastResponse();
        }

        return $response;
    }

    public function getRating($take_id, $user_id = null) {

        $params = [
            'mode' => 'first',
            'with' => ['questions'],
            'filter' => [
                'discussing_at_test_take_id' => $take_id,
                'rated' => 0
            ]
        ];

        if(isset($user_id)) {
            $params['filter']['user_id'] = $user_id;
        }

        $response = $this->Connector->getRequest('/answer_rating', $params);

        if($response === false){
            return $this->Connector->getLastResponse();
        }

        return $response;
    }

    public function setRating($rating_id, $rating) {
        $data['rating'] = $rating;

        $response = $this->Connector->putRequest('/answer_rating/' . $rating_id, [], $data);

        if($response === false){
            return $this->Connector->getLastResponse();
        }

        return $response;
    }

    public function finishDiscussion($take_id) {

        $test_take['test_take_status_id'] = 8;

        $response = $this->Connector->putRequest('/test_take/' . $take_id, [], $test_take);

        if($response === false){
            return $this->Connector->getLastResponse();
        }

        return $response;
    }

    public function updateShowResults($take_id, $active, $date) {

        if($active) {
            $test_take['show_results'] = date('Y-m-d H:i:00', strtotime($date));
        }else{
            $test_take['show_results'] = null;
        }

        $response = $this->Connector->putRequest('/test_take/' . $take_id, [], $test_take);

        if($response === false){
            return $this->Connector->getLastResponse();
        }

        return $response;
    }

    public function nextDiscussionQuestion($take_id) {
        $response = $this->Connector->postRequest('/test_take/' . $take_id . '/next_question', [], []);

        if($response === false){
            return $this->Connector->getLastResponse();
        }

        return $response;
    }

    public function startParticpantTest($take_id, $participant_id) {

        $data['test_take_status_id'] = 3;

        $response = $this->Connector->putRequest('/test_take/' . $take_id . '/test_participant/' . $participant_id, [], $data);

        if($this->Connector->getLastCode() == 422) {
            return false;
        }

        if($response === false){
            return $this->Connector->getLastResponse();
        }

        return $response;
    }

    public function startParticpantDiscussing($take_id, $participant_id) {

        $data['test_take_status_id'] = 7;

        $response = $this->Connector->putRequest('/test_take/' . $take_id . '/test_participant/' . $participant_id, [], $data);

        if($response === false){
            return $this->Connector->getLastResponse();
        }

        return $response;
    }

    public function editTestTake($take_id, $test_take) {

        $test_take['time_start'] = date('Y-m-d H:i:s', strtotime($test_take['time_start']));


        $response = $this->Connector->putRequest('/test_take/' . $take_id, [], $test_take);

        if($response === false){
            return $this->Connector->getLastResponse();
        }

        return $response;
    }

    public function getTestTakes($params)
    {

        if(!isset($params['order'])) {
            $params['order'] = ['id' => 'desc'];
        }

        $response = $this->Connector->getRequest('/test_take', $params);

        if($response === false){
            return $this->Connector->getLastResponse();
        }

        return $response;
    }

    public function getParticipantQuestion($question_id) {
        $response = $this->Connector->getRequest('/question/' . $question_id, []);

        if($response === false){
            return $this->Connector->getLastResponse();
        }

        return $response;
    }


    public function getParticipantStatusQuestionsAndAnswersForOverview2019($participant_id){
        $response = $this->Connector->getRequest('/test_participant/' . $participant_id . '/answers_status_and_questions2019', []);


        if($response === false){
            $this->addError($this->Connector->getLastResponse());
            return false;
        }

        return $response;
    }
    /**
     * WITH test take
     * @param $participant_id
     * @param $take_id
     * @return bool|mixed
     */
    public function getParticipantTestTakeStatusAndQuestionsForProgressList2019($participant_id, $take_id){
        $response = $this->Connector->getRequest('/test_participant/' . $participant_id . '/'.$take_id.'/answers_status_and_test_take2019', []);


        if($response === false){
            $this->addError($this->Connector->getLastResponse());
            return false;
        }

        return $response;
    }

    /**
     * WITHOUT test take
     * @param $participant_id
     * @return bool|mixed
     */
    public function getParticipantStatusAndQuestionsForProgressList2019($participant_id){
        $response = $this->Connector->getRequest('/test_participant/' . $participant_id . '/answers_and_status2019', []);


        if($response === false){
            $this->addError($this->Connector->getLastResponse());
            return false;
        }

        return $response;
    }

    public function getParticipantQuestions($participant_id) {

        $data['mode'] = 'all';
        $data['with'] = ['question'];

        $response = $this->Connector->getRequest('/test_participant/' . $participant_id . '/answer', $data);

        if($response === false){
            return $this->Connector->getLastResponse();
        }

        return $response;
    }

    public function lostFocus($take_id, $participany_id, $reason='') {

        $data['test_participant_id'] = $this->getParticipant($take_id, $participany_id)['id'];
        $data['test_take_event_type_id'] = 3;

        if ($reason !== '') {
            $data['test_take_event_type_id'] = 10; // for now only if alt+ tab
            $data['reason'] = $reason;
        }

        $response = $this->Connector->postRequest('/test_take/' . $take_id . '/test_take_event', $data, []);

        if($response === false){
            return $this->Connector->getLastResponse();
        }

        return $response;
    }

    public function screenshotDetected($take_id, $participany_id) {

        $data['test_participant_id'] = $this->getParticipant($take_id, $participany_id)['id'];
        $data['test_take_event_type_id'] = 4;

        $response = $this->Connector->postRequest('/test_take/' . $take_id . '/test_take_event', $data, []);

        if($response === false){
            return $this->Connector->getLastResponse();
        }

        return $response;
    }

    public function CheckForLogin($take_id, $participant_id) {
        $data['test_participant_id'] = $this->getParticipant($take_id, $participant_id)['id'];
        $data['test_take_event_type_id'] = 9;

        $response = $this->Connector->postRequest('/test_take/' . $take_id . '/test_take_event', $data, []);

        if($response === false){
            return $this->Connector->getLastResponse();
        }

        return $response;
    }

    public function heartBeat($take_id, $participant_id, $answer_id) {

        $data['ip_address'] = $_SERVER['REMOTE_ADDR'];

        if(!empty($answer_id)) {
            $data['answer_id'] = $answer_id;
        }

        $path = '/test_take/' . $take_id . '/test_participant/' . $participant_id . '/heartbeat';
        $response = $this->Connector->postRequest($path, $data, []);

        if($response === false){
            return $this->Connector->getLastResponse();
        }

        return $response;
    }

    public function removeEvent($take_id, $event_id) {

        $data['confirmed'] = 1;

        $response = $this->Connector->putRequest('/test_take/' . $take_id . '/test_take_event/' . $event_id, $data, []);

        if($response === false){
            return $this->Connector->getLastResponse();
        }

        return $response;
    }

    public function setParticipantRating($test_take_id, $participant_id, $rate) {

        $data['rating'] = $rate;

        $response = $this->Connector->putRequest('/test_take/' .$test_take_id. '/test_participant/' . $participant_id, [],  $data);

        echo $this->Connector->getLastResponse();

        if($response === false){
            return $this->Connector->getLastResponse();
        }

        return $response;
    }

    public function markRated($take_id) {
        $test_take['test_take_status_id'] = 9;

        $response = $this->Connector->putRequest('/test_take/' . $take_id, [], $test_take);

        if($response === false){
            return $this->Connector->getLastResponse();
        }

        return $response;
    }

    public function getTestTake($take_id, $params = []) {



        $response = $this->Connector->getRequest('/test_take/' . $take_id, $params);

        if($this->Connector->getLastCode() == 403) {
            return false;
        }

        if($response === false){
            return $this->Connector->getLastResponse();
        }

        return $response;
    }

    public function getTestTakeScore($take_id, $params = []) {
        $response = $this->Connector->getRequest('/test_take_max_score/' . $take_id, $params);
        if($this->Connector->getLastCode() == 403) {
            return false;
        }

        if($response === false){
            return $this->Connector->getLastResponse();
        }

        return $response;
    }

    public function getTestTakeAnswers($take_id) {
        $response = $this->Connector->getRequest('/test_take/' . $take_id, ['with' => ['answers', 'participantStatus']]);

        if($response === false){
            return $this->Connector->getLastResponse();
        }

        return $response;
    }

    public function getParticipantQuestionAnswer($participant_id, $question_id) {

        $params = [
            'filter' => [
                'question_id' => $question_id
            ],
            'with' => ['answer_ratings'],
            'mode' => 'all'
        ];

        $response = $this->Connector->getRequest('/test_participant/' . $participant_id . '/answer', $params);

        if($response === false){
            return $this->Connector->getLastResponse();
        }

        return $response;
    }

    public function getParticipants($take_id, $params = []) {

        $params['mode'] = 'all';

        $response = $this->Connector->getRequest('/test_take/' . $take_id . '/test_participant', $params);

        if($response === false){
            return $this->Connector->getLastResponse();
        }

        return $response;
    }

    public function addParticipants($take_id, $users) {

        $data['test_participant_ids'] = $users;
        $data['test_take_status_id'] = 1;

        $response = $this->Connector->postRequest('/test_take/' . $take_id . '/test_participant', [], $data);

        if($response === false){
            return $this->Connector->getLastResponse();
        }

        return $response;
    }

    public function saveNormalization($take_id, $postData, $preview = false) {

        $data = [
            'ignore_questions' => []
        ];

        if(isset($postData['Question'])) {
            foreach ($postData['Question'] as $question_id => $value) {
                $data['ignore_questions'][] = $question_id;
            }
        }

        $data['preview'] = $preview ? 1 : 0;

        if($postData['TestTake']['type'] == 1) {
            $data['ppp'] = str_replace(',', '.', $postData['TestTake']['value_1']);
        }elseif($postData['TestTake']['type'] == 2) {
            $data['wanted_average'] = str_replace(',', '.', $postData['TestTake']['value_2']);
        }elseif($postData['TestTake']['type'] == 3) {
            $data['n_term'] = str_replace(',', '.', $postData['TestTake']['value_3']);
        }elseif($postData['TestTake']['type'] == 4) {
            $data['epp'] = str_replace(',', '.', $postData['TestTake']['value_4']);
        }elseif($postData['TestTake']['type'] == 5) {
            $data['n_term'] = str_replace(',', '.', $postData['TestTake']['value_5']);
            $data['pass_mark'] = str_replace(',', '.', $postData['TestTake']['value_6']);
        }

        $response = $this->Connector->postRequest('/test_take/' . $take_id .'/normalize', [], $data);

        if($response === false){
            return $this->Connector->getLastResponse();
        }

        return $response;
    }

    public function getRandomAnswerRatings($take_id, $percentage) {

        $data['filter'] = [
            'test_take_id' => $take_id
        ];
        $data['mode'] = 'all';

        $response = $this->Connector->getRequest('/answer_rating', $data);

        if($response === false){
            return $this->Connector->getLastResponse();
        }

        $results = [];

        foreach($response as $answer) {
            $results[$answer['answer_id']][] = $answer;
        }

        $matches = [];

        foreach($results as $answer_id => $ratings) {
            $student1 = null;
            $student2 = null;
            $teacher = null;
            $system = false;

            foreach($ratings as $rating) {
                if($rating['type'] == 'SYSTEM') {
                    $system = true;
                }elseif($rating['type'] == 'STUDENT') {
                    if(empty($student1)) {
                        $student1 = $rating['rating'];
                    }elseif(empty($student2)) {
                        $student2 = $rating['rating'];
                    }
                }elseif($rating['type'] == 'TEACHER') {
                    $teacher = $rating['rating'];
                }
            }

            if(!empty($student1) && $student1 == $student2 && !$system) {
                $matches[] = $rating;
            }
        }

        shuffle($matches);

        $matches = array_slice($matches, 0, round(((count($matches) / 100) * $percentage)));

        return $matches;
    }

    public function saveTeacherScore($answer_id, $rating, $user_id, $take_id) {

        $data['answer_id'] = $answer_id;
        $data['rating'] = $rating;
        $data['user_id'] = $user_id;
        $data['test_take_id'] = $take_id;
        $data['type'] = 'TEACHER';
        $data['advise'] = 'test';

        $response = $this->Connector->postRequest('/answer_rating', $data, []);

        if($response === false){
            return $this->Connector->getLastResponse();
        }

        return $response;
    }

    public function updateTeacherScore($rating_id, $rating) {

        $data['rating'] = $rating;

        $response = $this->Connector->putRequest('/answer_rating/' . $rating_id, $data, []);

        if($response === false){
            return $this->Connector->getLastResponse();
        }

        return $response;
    }

    public function getParticipant($take_id, $participant_id) {
        $response = $this->Connector->getRequest('/test_take/' . $take_id . '/test_participant/' . $participant_id, [
            'with' => ['participantStatus', 'statistics']
        ]);

        if($response === false){
            return $this->Connector->getLastResponse();
        }

        return $response;
    }

    public function setParticipantNote($take_id, $participant_id, $note) {

        $data['invigilator_note'] = $note;

        $response = $this->Connector->putRequest('/test_take/' . $take_id . '/test_participant/' . $participant_id, $data, []);

        if($response === false){
            return $this->Connector->getLastResponse();
        }

        return $response;
    }



    public function removeParticipant($take_id, $participant_id) {
        $response = $this->Connector->deleteRequest('/test_take/' . $take_id . '/test_participant/' . $participant_id, []);

        if($response === false){
            return $this->Connector->getLastResponse();
        }

        return $response;
    }

    public function addClass($take_id, $class_id) {

        $data['school_class_ids'] = [$class_id];
        $data['test_take_status_id'] = 1;

        $response = $this->Connector->postRequest('/test_take/' . $take_id . '/test_participant', $data, []);


        if($response === false){
            return $this->Connector->getLastResponse();
        }

        return $response;
    }

    public function getExport($take_id) {

        $response = $this->Connector->getDownloadRequest('/test_take/' . $take_id . '/export', []);

        if($response === false){
            return $this->Connector->getLastResponse();
        }

        return $response;
    }

    public function getClassStudents($class_id, $mode=false) {

        $params = [
            'filter' => [
                'school_class_id' => $class_id
            ],
            'order' => [
                'name' => 'asc'
            ]
        ];

        if ($mode) {
            $params['mode'] = $mode;
        }

        $response = $this->Connector->getRequest('/student', $params, []);

        if($response === false){
            return $this->Connector->getLastResponse();
        }

        if($mode && $mode === 'all'){
            return $response;
        }
        return $response['data'];
    }

    public function addClassStudents($take_id, $class_id, $students)
    {

        $data['school_class_id'] = $class_id;
        $data['user_id'] = $students;
        $data['test_take_status_id'] = 1;

        $response = $this->Connector->postRequest('/test_take/' . $take_id . '/test_participant', $data, []);

        if($response === false){
            return $this->Connector->getLastResponse();
        }
    }

    public function updateExportedToRtti($take_id)
    {
        $params['exported_to_rtti'] = date('Y-m-d H:i:s', strtotime('now'));
        // $params['exported_to_rtti'] = strtotime('now');
        $response = $this->Connector->putRequest('/test_take/' . $take_id , $params, []);

        if($response === false){
            return $this->Connector->getLastResponse();
        }

        return $response;
    }

    public function archive($test_id)
    {
        $response = $this->Connector->putRequest('/test_take/'.$test_id.'/archive', [], []);
        if($response === false){
            return $this->Connector->getLastResponse();
        }

        return $response;
    }

    public function unArchive($test_id)
    {
        $response = $this->Connector->putRequest('/test_take/'.$test_id.'/un-archive', [], []);
        if($response === false){
            return $this->Connector->getLastResponse();
        }

        return $response;
    }

    public function toggleInbrowserTestingForParticipant($test_take_id, $participant_id) {
        $response = $this->Connector->putRequest(
            sprintf('/test_take/%s/test_participant/%s/toggle_inbrowser_testing', $test_take_id, $participant_id),
            [],
            []
        );
        if($response === false){
            return $this->Connector->getLastResponse();
        }

        return $response;
    }
    public function getDrawingAnswerUrl($answer_uuid)
    {
        $response = $this->Connector->getRequest('/test_participant/drawing_answer_url/'.$answer_uuid,[],[]);

        if($response === false){
            return $this->Connector->getLastResponse();
        }

        return $response;
    }

    public function hasCarouselQuestion($test_take_id)
    {
        $response = $this->Connector->getRequest('/test_take/'. $test_take_id .'/has_carousel_question/', [], []);

        if($response === false){
            return $this->Connector->getLastResponse();
        }

        return $response;
    }
}
