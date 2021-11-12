<?php

App::uses('AppController', 'Controller');
App::uses('TestsService', 'Lib/Services');
App::uses('QuestionsService', 'Lib/Services');
App::uses('AnswersService', 'Lib/Services');
App::uses('AttachmentsService', 'Lib/Services');
App::uses('SchoolLocationsService', 'Lib/Services');

class CitoTestsController extends AppController {

    public $uses = array('Test', 'Question');

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


}
