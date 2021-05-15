<?php

App::uses('AppController', 'Controller');
App::uses('UsersService', 'Lib/Services');
App::uses('TestsService', 'Lib/Services');
App::uses('SchoolClassesService', 'Lib/Services');
App::uses('SchoolLocationsService', 'Lib/Services');
App::uses('TestTakesService', 'Lib/Services');
App::uses('MessagesService', 'Lib/Services');
App::uses('HelperFunctions','Lib');

class AnalysesController extends AppController
{
    public function beforeFilter()
    {
        $this->UsersService = new UsersService();
        $this->TestsService = new TestsService();
        $this->SchoolClassesService = new SchoolClassesService();
        $this->SchoolLocationsService = new SchoolLocationsService();
        $this->TestTakesService = new TestTakesService();
        $this->MessagesService = new MessagesService();

        parent::beforeFilter();
    }

    public function school_class($class_id) {
        $class = $this->SchoolClassesService->getClass($class_id, [
            'with' => ['schoolClassStats']
        ]);

        $test_takes = $this->TestTakesService->getTestTakes([
            'filter' => [
                'school_class_id' => $class_id,
                'test_take_status_id' => [6, 7, 8, 9]
            ],
            'mode' => 'all'
        ]);

        $students = $class['student_users'];
        $teachers = $class['teacher'];
        $subjects = HelperFunctions::getInstance()->revertSpecialChars($class['subjects']);
        $subject_stats = $class['subjects_stats'];
        $parallel_classes = HelperFunctions::getInstance()->revertSpecialChars($class['parallel_school_classes']);

        $this->set('students', $students);
        $this->set('teachers', $teachers);
        $this->set('subjects', $subjects);
        $this->set('subjects_stats', $subject_stats);
        $this->set('parallel_classes', $parallel_classes);
        $this->set('class', $class);
        $this->set('test_takes', $test_takes);
    }

    public function school_classes_overview() {
        $subjects = $this->TestsService->getSubjects(false, 'all');

        $this->set('is_temp_teacher', AuthComponent::user('is_temp_teacher'));

        $this->Session->write('subjects', $subjects);
        $this->set('subjects', $subjects);
    }

    public function load_school_classes_overview() {
        $params = $this->request->data;

        $params['with'] = ['schoolClassStats'];
        $params['filter'] = ['is_main_school_class' => 1, 'current_school_year' => 1];

        $classes = $this->TestsService->getClassesItems($params);

        $this->set('subjects', $this->Session->read('subjects'));
        $this->set('classes', $classes['data']);
    }

    public function teacher($user_id) {
        $teacher = $this->UsersService->getUser($user_id, ['with' => ['teacherComparison', 'teacherSchoolClassAverages']]);

        $this->set('is_temp_teacher', AuthComponent::user('is_temp_teacher'));

        $this->set('teacher', $teacher);
        $this->set('user_id', $user_id);
    }

    public function teachers_overview() {

    }

    public function load_teachers() {
        $params = $this->request->data;

        $params['filter'] = [
            'role' => 1
        ];

        $users = $this->UsersService->getUsers($params);

        $this->set('users', $users);
    }

    public function student($user_id) {
        $this->set('subjects', HelperFunctions::getInstance()->revertSpecialChars($this->TestsService->getSubjects()));

        $student = $this->UsersService->getUser($user_id, ['with' => ['studentAverageGraph', 'studentSubjectAverages', 'testsParticipated']]);

        $subjects = [];
        $base_subjects = [];

        foreach($student['subjects'] as $subject) {
            $subjects[getUUID($subject, 'get')] = $subject['name'];
        }

        foreach($student['base_subjects'] as $base_subject) {
            $base_subjects[getUUID($base_subject, 'get')] = $base_subject['name'];
        }

        $messages = $this->MessagesService->getMessages([
            'filter' => [
                'receiver_id' => [$user_id, AuthComponent::user('id')],
                'sender_id' => [$user_id, AuthComponent::user('id')]
            ]
        ]);

        $this->set('messages', $messages['data']);
        $this->set('subjects', $subjects);
        $this->set('base_subjects', $base_subjects);
        $this->set('user_id', $user_id);
        $this->set('student', $student);
    }

    public function student_endterms($user_id) {

        $data = $this->request->data['StudentEndterms'];

        $student = $this->UsersService->getUser($user_id, [
            'with' => [
                'studentPValues' => [
                    'subjectId' => $data['subject_id']
                ]
            ]
        ]);

        if(!isset($student['developed_attainments']) || empty($student['developed_attainments'])) {
            die(__('Grafiek kon niet worden gegenereerd'));
        }

        $this->set('attainments', $student['developed_attainments']);
    }

    public function student_subject_ratings($user_id) {
        $data = $this->request->data['StudentRatings'];

        if(isset($data['subject_id']) && !empty($data['subject_id'])) {
            $params = [
                'with' => [
                    'studentAverageGraph' => [
                        'subjectId' => $data['subject_id'],
                        'percentage' => $data['score_type'] == 'percentages' ? true : false
                    ]
                ]
            ];
        }elseif(isset($data['base_subject_id']) && !empty($data['base_subject_id'])) {
            $params = [
                'with' => [
                    'studentAverageGraph' => [
                        'baseSubjectId' => $data['base_subject_id'],
                        'percentage' => $data['score_type'] == 'percentages' ? true : false
                    ]
                ]
            ];
        }


        $student = $this->UsersService->getUser($user_id, $params);

        if(!isset($student['subjects']) && empty($student['subjects'])) {
            die(__('Grafiek kon niet worden gegenereerd'));
        }else{
            $this->set('student', $student);
            $this->set('subjects', $student['subjects']);
            $this->set('subject_id', $data['subject_id']);
            $this->set('type', $data['score_type']);
        }
    }

    public function students_overview() {
        $_subjects = $this->TestsService->getSubjects(false, 'all');

        $subjects = [];
        foreach($_subjects as $subject){
            $subject['name'] = HelperFunctions::getInstance()->revertSpecialChars($subject['name']);
            $subject['base_subject']['name'] = HelperFunctions::getInstance()->revertSpecialChars($subject['base_subject']['name']);
            $subjects[] = $subject;
        }
        $this->Session->write('subjects', $subjects);
        $this->set('subjects', $subjects);

        $params['filter'] = ['current_school_year' => 1];
        
        $school_locations[0] = __('Alle');
        $school_locations_list = $this->SchoolLocationsService->getSchoolLocationList();

        if (!empty($school_locations_list) && is_array($school_locations_list)) {
            $school_locations += $school_locations_list;
        }

        $school_classes_list = $this->SchoolClassesService->getClassesList($params);

        $school_classes[0] = __('Alle');

        if (!empty($school_classes_list) && is_array($school_classes_list)) {
            $school_classes += $school_classes_list;
        }

        $school_classes = HelperFunctions::getInstance()->revertSpecialChars($school_classes);

        $this->set('is_temp_teacher', AuthComponent::user('is_temp_teacher'));

        $this->set('school_classes', $school_classes);
        $this->set('school_location', $school_locations);
    }

    public function load_students_overview() {
        $params = $this->request->data;

        $filters = array();
        parse_str($params['filters'], $filters);
        $filters = $filters['data']['User'];

        unset($params['filters']);

        $params['filter'] = [];

        if(!empty($filters['name'])) {
            $params['filter']['name'] = $filters['name'];
        }

        if(!empty($filters['name_first'])) {
            $params['filter']['name_first'] = $filters['name_first'];
        }

        if(isset($filters['external_id']) && !empty($filters['external_id'])) {
            $params['filter']['external_id'] = $filters['external_id'];
        }

        if(isset($filters['school_location_id']) && !empty($filters['school_location_id'])) {
            $params['filter']['school_location_id'] = $filters['school_location_id'];
        }

        if(isset($filters['school_class_id']) && !empty($filters['school_class_id'])) {
            $params['filter']['school_class_id'] = $filters['school_class_id'];
        }

        $params['with'] = ['studentSubjectAverages'];
        $params['filter']['role'] = 3;
        $params['filter']['teacher_students'] = true;

        $users = $this->UsersService->getUsers($params);

        $this->set('subjects', $this->Session->read('subjects'));
        $this->set('users', $users);
    }
}