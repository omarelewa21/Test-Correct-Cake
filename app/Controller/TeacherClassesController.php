<?php

App::uses('AppController', 'Controller');
App::uses('SchoolClassesService', 'Lib/Services');
App::uses('SectionsService', 'Lib/Services');
App::uses('UsersService', 'Lib/Services');
App::uses('TestsService', 'Lib/Services');
App::uses('SchoolLocationsService', 'Lib/Services');
App::uses('SchoolYearsService', 'Lib/Services');
App::uses('File', 'Utility');

class TeacherClassesController extends AppController
{

    public function beforeFilter()
    {
        $this->SchoolClassesService = new SchoolClassesService();
        $this->UsersService = new UsersService();
        $this->SchoolLocationService = new SchoolLocationsService();

        parent::beforeFilter();
    }

    public function index()
    {
        $has_lvs = false;
        $has_incomplete_import = false;
        if($this->UsersService->hasRole('Teacher')){
            $has_incomplete_import = $this->UsersService->shouldDisplayImportIncompletePanel();
            $has_lvs = (bool) $this->SchoolLocationService->getLvsType(
                getUUID(AuthComponent::user('school_location'), 'get')
            )[0];
        }
        $this->set('has_lvs',$has_lvs);
        $this->set('has_incomplete_import',$has_incomplete_import);
    }

    public function load()
    {
        $params = $this->handleRequestOrderParameters($this->request->data);

        $params = array_merge([
            'filter' => ['current_school_year' => 1],
        ],$params);

        $classes  = $this->SchoolClassesService->getClasses($params);

        $this->set('classes', $classes);
    }

    public function view($class_id) {
        $class = $this->SchoolClassesService->getClass($class_id);
        $user = $this->UsersService->getUser(getUUID(AuthComponent::user(), 'get'));
        $this->set('user',$user);
        $this->set('class', $class);
    }

    public function load_students($class_id, $location_id) {
        $params['filter'] = ['student_school_class_id' => $this->SchoolClassesService->getClass($class_id)['id']];
        $students = $this->UsersService->getUserList($params);
        $this->set('students', $students);
        $this->set('class_id', $class_id);
        $this->set('location_id',$location_id);
    }

    public function edit_student($user_id) {

        if($this->request->is('post') || $this->request->is('put')) {

            $data = $this->request->data['User'];

            $result = $this->UsersService->updateUser($user_id, $data);

            $this->formResponse(
                $result ? true : false,
                []
            );

            die;
        }

        $this->request->data['User'] = $this->UsersService->getUser($user_id);
    }

    public function school_location_classes()
    {
        $this->isAuthorizedAs(['Teacher']);
    }

    public function load_school_location_classes()
    {
        $params = $this->handleRequestOrderParameters($this->request->data);

        $params = array_merge(
            array('mode' => 'all_classes_for_location'),
            $params
        );

        $classes  = $this->SchoolClassesService->getClasses($params);

        $this->set('classes', $classes);
        $this->render('load_school_location_classes', 'ajax');
    }
}