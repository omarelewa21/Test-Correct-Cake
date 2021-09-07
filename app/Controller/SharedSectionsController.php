<?php

App::uses('AppController', 'Controller');
App::uses('TestsService', 'Lib/Services');
App::uses('QuestionsService', 'Lib/Services');
App::uses('AnswersService', 'Lib/Services');
App::uses('AttachmentsService', 'Lib/Services');
App::uses('SchoolLocationsService', 'Lib/Services');

class SharedSectionsController extends AppController {

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
        $this->isAuthorizedAs(["School manager"]);

    }

    public function load() {
        $this->isAuthorizedAs(["School manager"]);


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

//        if (!empty($filters['subject'])) {
//            $params['filter']['subject_id'] = $filters['subject'];
//        }

//        if (!empty($filters['period'])) {
//            $params['filter']['period_id'] = $filters['period'];
//        }

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

        $params = $this->handleRequestOrderParameters($params, 'name');
        $tests = $this->TestsService->getSharedSectionsTests($params);

        $this->set('tests', $tests['data']);
    }

    public function duplicate($test_id)
    {
        $this->isAuthorizedAs(["Teacher"]);

        if ($this->request->is('post') ) {
            $data = $this->request->data['Test'];
            $response = $this->TestsService->duplicateSharedSectionsTest($test_id,$data);
            if($response === false){
                if(substr_count('The name has already been taken.',json_encode($this->TestsService->getErrors())) > 0){
                    $this->formResponse(false, [
                        'message' => 'Deze naam heb je inmiddels in gebruik voor een van je toetsen.'
                    ]);

                } else {
                    $this->formResponse(false, [
                        'message' => 'Er is iets fout gegaan, probeer het nogmaals.'
                    ]);
                }
            } else {
                $this->formResponse(true,['test_id' => getUUID($response,'get')]);
            }
            exit;
        }

        $test = $this->TestsService->getSharedSectionsTest($test_id);

        $subjects = $this->TestsService->getSubjects();

        $this->set('subjects',$subjects);

        $this->set('test',$test);

    }

}
