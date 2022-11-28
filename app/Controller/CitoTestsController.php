<?php

App::uses('AppController', 'Controller');
App::uses('TestsService', 'Lib/Services');
App::uses('QuestionsService', 'Lib/Services');
App::uses('AnswersService', 'Lib/Services');
App::uses('AttachmentsService', 'Lib/Services');
App::uses('SchoolLocationsService', 'Lib/Services');
App::uses('CarouselMethods', 'Trait');

class CitoTestsController extends AppController {

    public $uses = array('Test', 'Question');

    use CarouselMethods;

    public $carouselGroupQuestionNotifyMsg = '';

    public function beforeFilter() {
        $this->TestsService = new TestsService();
        $this->QuestionsService = new QuestionsService();
        $this->AnswersService = new AnswersService();
        $this->AttachmentsService = new AttachmentsService();
        $this->SchoolLocationsService = new SchoolLocationsService();

        parent::beforeFilter();
    }

    public function test() {}

    public function index() {
        $this->isAuthorizedAs(["Teacher", "Invigilator"]);

        $education_level_years = [
            0 => __("Alle"),
            1 => 1,
            2 => 2,
            3 => 3,
            4 => 4,
            5 => 5,
            6 => 6
        ];

        $education_levels = $this->TestsService->getEducationLevels();
        $periods = $this->TestsService->getPeriods();
        $_subjects = $this->TestsService->getCitoSubjects(true);
        foreach($_subjects as $key => $val){
            if(substr(strtolower($val),0,4) == 'cito'){
                $subjects[$key] = $val;
            }
        }
        $kinds = $this->TestsService->getKinds();

        $education_levels = [0 => __("Alle")] + $education_levels;
        $periods = [0 => __("Alle")] + $periods;
        $subjects = [0 => __("Alle")] + $subjects;
        $kinds = [0 => __("Alle")] + $kinds;

        $this->set('education_levels', $education_levels);
        $this->set('education_level_years', $education_level_years);
        $this->set('kinds', $kinds);
        $this->set('periods', $periods);
        $this->set('subjects', $subjects);
    }

    public function load() {
        $this->isAuthorizedAs(["Teacher", "Invigilator"]);

        $education_levels = $this->TestsService->getEducationLevels(true, false);

        $periods = $this->TestsService->getPeriods();
        $subjects = $this->TestsService->getSubjects();
        $kinds = $this->TestsService->getKinds();

        $this->set('education_levels', $education_levels);
        $this->set('kinds', $kinds);
        $this->set('periods', $periods);
        $this->set('subjects', $subjects);

        $params = $this->request->data;

        $filters = array();
        parse_str($params['filters'], $filters);
        $filters = $filters['data']['Test'];

        unset($params['filters']);

        $params['filter'] = [];

        if (!empty($filters['name'])) {
            $params['filter']['name'] = $filters['name'];
        }

        if (!empty($filters['kind'])) {
            $params['filter']['test_kind_id'] = $filters['kind'];
        }

        if (!empty($filters['subject'])) {
            $params['filter']['subject_id'] = $filters['subject'];
        }

        if (!empty($filters['period'])) {
            $params['filter']['period_id'] = $filters['period'];
        }

        if (!empty($filters['education_levels'])) {
            $params['filter']['education_level_id'] = $filters['education_levels'];
        }

        if (!empty($filters['education_level_years'])) {
            $params['filter']['education_level_year'] = $filters['education_level_years'];
        }

        if (!empty($filters['created_at_start'])) {
            $params['filter']['created_at_start'] = date('Y-m-d 00:00:00', strtotime($filters['created_at_start']));
        }

        if (!empty($filters['created_at_end'])) {
            $params['filter']['created_at_end'] = date('Y-m-d 00:00:00', strtotime($filters['created_at_end']));
        }

        if(!empty($filters['is_open_sourced_content']) && $filters['is_open_sourced_content'] != 0) {
          $params['filter']['is_open_sourced_content'] = ($filters['is_open_sourced_content'] == 2) ? 1 : 0;
        }

        $params = $this->handleRequestOrderParameters($params);
        $tests = $this->TestsService->getCitoTests($params);

        $this->set('tests', $tests['data']);
    }

    public function view($test_id)
    {
        $this->isAuthorizedAs(["Teacher", "Invigilator"]);

        $test = $this->TestsService->getTest($test_id);


        $this->Session->write('active_test', $test);

        $questions = $this->TestsService->getQuestions($test_id);

        $questionsArray = array();
        $totalScore = $this->TestsService->getTestScore($test_id,[]);

        $this->set('carouselGroupQuestionNotify', false);

        foreach ($questions as $question) {

            $question['question'] = $this->QuestionsService->decodeCompletionTags($question['question']);

            if ($question['question']['type'] == 'CompletionQuestion') {
                $question['question']['question'] = $this->stripTagsWithoutMath($question['question']['question']);
            }

            if ($question['question']['type'] == 'GroupQuestion') {
                for ($i = 0; $i < count($question['question']['group_question_questions']); $i++) {

                    //fix for TC-80 / Selenium tests. The selection options were empty for group questions
                    $question['question']['group_question_questions'][$i]['question'] = $this->QuestionsService->decodeCompletionTags($question['question']['group_question_questions'][$i]['question']);

                    //$totalScore += $question['question']['group_question_questions'][$i]['question']['score'];
                    $question['question']['group_question_questions'][$i]['question']['question'] = strip_tags($question['question']['group_question_questions'][$i]['question']['question']);
                }
                $this->setNotificationsForViewGroup($question['question']);
            }
            array_push($questionsArray, $question);
        }

        $education_levels = $this->TestsService->getEducationLevels();
        $periods = $this->TestsService->getPeriods();
        $subjects = $this->TestsService->getSubjects();
        $kinds = $this->TestsService->getKinds();
//        for($i = 1; $i < count($kinds)+1; $i++){
//            $kinds[$i] = __("$kinds[$i]");
//        }
        foreach($kinds as $key => $kind) {
            $kinds[$key] = __($kind);
        }

        $this->set('totalScore', $totalScore);
        if($msg != ''){
            $this->set('totalScore', '<i class="fa fa-exclamation-triangle" title="'.$msg.'"></i>');
        }

        $this->set('education_levels', $education_levels);
        $this->set('kinds', $kinds);
        $this->set('periods', $periods);
        $this->set('subjects', $subjects);
        $this->set('test', $test);
        $this->set('canEdit', $test['author']['id'] == AuthComponent::user()['id'] && $test['status'] != 1);
        $this->set('questions', $questionsArray);
        $this->set('test_id', $test_id);

        $newPlayerAccess = in_array($test['owner']['allow_new_player_access'], [1,2]);
        $oldPlayerAccess = in_array($test['owner']['allow_new_player_access'], [0,1]);
        $this->set('newPlayerAccess', $newPlayerAccess);
        $this->set('oldPlayerAccess', $oldPlayerAccess);
        $this->set('startWithEdit',false);
        $this->set('newEditor', AuthComponent::user('school_location.allow_new_question_editor') ?? 0);
        $this->set('returnPath','/cito_tests/index');
    }

}
