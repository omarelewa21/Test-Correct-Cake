<?php

App::uses('AppController', 'Controller');
App::uses('SchoolClassesService', 'Lib/Services');
App::uses('SectionsService', 'Lib/Services');
App::uses('UsersService', 'Lib/Services');
App::uses('TestsService', 'Lib/Services');
App::uses('SchoolLocationsService', 'Lib/Services');
App::uses('SchoolYearsService', 'Lib/Services');
App::uses('File', 'Utility');
App::uses('HelperFunctions', 'Lib');

class SchoolClassesController extends AppController
{

    public function beforeFilter()
    {
        $this->SchoolClassesService = new SchoolClassesService();
        $this->SchoolLocationsService = new SchoolLocationsService();
        $this->SectionsService = new SectionsService();
        $this->UsersService = new UsersService();
        $this->TestsService = new TestsService();
        $this->SchoolYearsService = new SchoolYearsService();

        parent::beforeFilter();
    }

    public function index()
    {
        $this->isAuthorizedAs(['Administrator', 'Account manager', 'School manager', 'School management']);
        $school_years1 = ['' => 'Kies een jaar'];
        $school_years2 = $this->SchoolYearsService->getSchoolYearList();
        $activeSchoolYearId = $this->SchoolYearsService->getActiveSchoolYearId();
        $school_years = $school_years1 + $school_years2;
        $this->set('currentYearId', $activeSchoolYearId);
        $this->set('school_years', $school_years);

    }

    public function load()
    {
        $this->isAuthorizedAs(['Administrator', 'Account manager', 'School manager', 'School management']);

        $params = $this->request->data;

        $filters = array();
        parse_str($params['filters'], $filters);

        $filters = $filters['data']['SchoolClass'];


        unset($params['filters']);
        $params['filter'] = [];

        //$params['filter'] = ['current_school_year' => 1];
        if (!empty($filters['name'])) {
            $params['filter']['name'] = $filters['name'];
        }
        if (!empty($filters['school_year_id'])) {
            $params['filter']['school_year_id'] = $filters['school_year_id'];
        }

        $classes = $this->SchoolClassesService->getClasses($params);


        $this->set('classes', $classes);
    }

    public function load_teachers($class_id)
    {
        $this->isAuthorizedAs(['Administrator', 'Account manager', 'School manager', 'School management']);

        $class = $this->SchoolClassesService->getClass($class_id);

        $this->set('class', $class);
    }

    public function view($class_id)
    {
        $this->isAuthorizedAs(['Administrator', 'Account manager', 'School manager', 'School management']);

        $class = $this->SchoolClassesService->getClass($class_id);
        $this->set('class', $class);
        $this->Session->write('class_id', $class_id);
    }

    public function load_students($class_id, $location_id)
    {
        $this->isAuthorizedAs(['Administrator', 'Account manager', 'School manager', 'School management']);

        $params['filter'] = ['student_school_class_id' => $this->SchoolClassesService->getClass($class_id)['id']];
        $students = $this->UsersService->getUserList($params);
        $this->set('students', $students);
        $this->set('class_id', $class_id);
        $this->set('location_id', $location_id);
        $this->set('class', $this->SchoolClassesService->getClass($class_id));
    }

    public function load_managers($class_id)
    {
        $this->isAuthorizedAs(['Administrator', 'Account manager', 'School manager', 'School management']);

        $class = $this->SchoolClassesService->getClass($class_id);

        $params['filter'] = ['manager_school_class_id' => $class['id']];
        $managers = $this->UsersService->getUserList($params);
        $this->set('managers', $managers);
        $this->set('class_id', $class_id);
        $this->set('class', $class);
    }

    public function load_mentors($class_id)
    {
        $this->isAuthorizedAs(['Administrator', 'Account manager', 'School manager', 'School management']);

        $class = $this->SchoolClassesService->getClass($class_id);

        $params['filter'] = ['mentor_school_class_id' => $class['id']];
        $mentors = $this->UsersService->getUserList($params);
        $this->set('mentors', $mentors);
        $this->set('class_id', $class_id);
        $this->set('class', $class);
    }

    public function doImport($location_id, $class_id)
    {
        $this->isAuthorizedAs(['Administrator', 'Account manager', 'School manager', 'School management']);

        $data['data'] = $this->request->data;
        $result = $this->SchoolClassesService->doImportStudents($location_id, $class_id, $data);
        if (!$result) {
            $response = $this->translateError($this->SchoolClassesService);
            $this->formResponse(false, $response);
            return false;
        }
        $this->formResponse(true, []);
    }


    public function import($location_id, $class_id)
    {
        $this->isAuthorizedAs(['Administrator', 'Account manager', 'School manager', 'School management']);

        $classes = $this->SchoolClassesService->getForLocationId($location_id, 'uuidlist');
        $this->set('classes', $classes);
        $this->set('class_id', $class_id);
        $this->set('location_id', $location_id);
        $this->set('school_location', $this->SchoolLocationsService->getSchoolLocation($location_id));
    }

    public function add()
    {
        $this->isAuthorizedAs(['Administrator', 'Account manager', 'School manager', 'School management']);

        // klas niet overschrijven checkbox 
        $school_location = AuthComponent::user('school_location');
        $this->set('is_rtti', $school_location['is_rtti_school_location']);
        $this->set('lvs_type', $school_location['lvs_type']);

        if ($this->request->is('post') || $this->request->is('put')) {

            $data = $this->request->data['SchoolClass'];

            $result = $this->SchoolClassesService->addClass($data);

            $decodedResult = json_decode($result);
            if (array_key_exists('errors', $decodedResult)) {
                if (array_key_exists('name', $decodedResult->errors)) {
                    $this->formResponse(
                        false,
                        $decodedResult->errors->name[0]
                    );
                }
            } elseif ($result == 'Failed to create school class') {
                $this->formResponse(
                    false,
                    'Klas kon niet worden aangemaakt'
                );
            } else {
                $this->formResponse(
                    true,
                    ['id' => $result['id'], 'uuid' => getUUID($result, 'get')]
                );
            }

            die;
        }

        $school_locations = array();
        foreach ($this->SchoolLocationsService->getSchoolLocations([]) as $key => $location) {
            $school_locations[getUUID($location, 'get')] = $location['is_rtti_school_location'];
        }

        $this->set('location_info', $school_locations);
        $this->set('locations', $this->SchoolLocationsService->getSchoolLocationList());
        $this->set('education_levels', $this->TestsService->getEducationLevels(false, false));
        $this->set('school_years', $this->SchoolYearsService->getSchoolYearList());
    }

    public function get_for_location($location_id)
    {
        $this->isAuthorizedAs(['Administrator', 'Account manager', 'School manager', 'School management']);

        $this->autoRender = false;
        $classes = $this->SchoolClassesService->getForLocationId($location_id);

        echo json_encode($classes);
    }

    public function edit($class_id)
    {
        $this->isAuthorizedAs(['Administrator', 'Account manager', 'School manager', 'School management']);

        if ($this->request->is('post') || $this->request->is('put')) {

            $data = $this->request->data['SchoolClass'];

            $result = $this->SchoolClassesService->updateClass($class_id, $data);

            $decodedResult = json_decode($result);
            if (array_key_exists('errors', $decodedResult)) {
                if (array_key_exists('name', $decodedResult->errors)) {
                    $this->formResponse(
                        false,
                        $decodedResult->errors->name[0]
                    );
                }
            } else {
                $this->formResponse(
                    true,
                    []
                );
            }

            die;
        }

        $this->set('is_rtti', $this->SchoolClassesService->getClass($class_id)['school_location']['is_rtti_school_location']);

        $this->set('locations', $this->SchoolLocationsService->getSchoolLocationList());
        $educationLevels = $this->TestsService->getEducationLevels(false, false);
        $this->set('education_levels', $educationLevels);
        $this->set('school_years', $this->SchoolYearsService->getSchoolYearList());
        $this->request->data['SchoolClass'] = $this->SchoolClassesService->getClass($class_id);
        $this->request->data['SchoolClass']['name'] = HelperFunctions::getInstance()->revertSpecialChars($this->request->data['SchoolClass']['name']);
        $this->set('SchoolClassEducationLevelUuid', $this->request->data['SchoolClass']['education_level']['uuid']);
        $this->set('SchoolClassEducationLevelYear', $this->request->data['SchoolClass']['education_level_year']);
        $this->set('initEducationLevelYears', $this->getInitEducationLevelYears($educationLevels, $this->request->data['SchoolClass']['education_level']['uuid']));
    }

    public function add_teacher($class_id)
    {
        $this->isAuthorizedAs(['Administrator', 'Account manager', 'School manager', 'School management']);

        if ($this->request->is('post') || $this->request->is('put')) {

            $data = $this->request->data['Teacher'];

            $result = $this->SchoolClassesService->addTeacher($class_id, $data);

            $this->formResponse(
                $result ? true : false,
                []
            );

            die;
        }

        $this->set('teachers', $this->UsersService->getUserList(['mode' => 'list', 'filter' => ['role' => [1]]], true));
        $this->set('subjects', HelperFunctions::getInstance()->revertSpecialChars($this->SectionsService->getSectionSubjectList()));
    }

    public function add_management($class_id)
    {
        $this->isAuthorizedAs(['Administrator', 'Account manager', 'School manager', 'School management']);

        if ($this->request->is('post') || $this->request->is('put')) {

            $class_id = $this->SchoolClassesService->getClass($class_id)['id'];

            $result = $this->SchoolClassesService->addManager($class_id, $this->request->data['Manager']['manager_id']);

            $this->formResponse(
                $result ? true : false,
                []
            );

            die;
        }

        $this->set('managements', $this->UsersService->getUserList(['mode' => 'list', 'filter' => ['role' => [7]]], true));
        $this->set('subjects', $this->SectionsService->getSectionSubjectList());
    }

    public function add_student($class_id)
    {
        $this->isAuthorizedAs(['Administrator', 'Account manager', 'School manager', 'School management']);

        if ($this->request->is('post') || $this->request->is('put')) {

            $result = $this->SchoolClassesService->addStudent($this->SchoolClassesService->getClass($class_id)['id'], $this->request->data['Student']['student_id']);

            $this->formResponse(
                $result ? true : false,
                []
            );

            die;
        }

        $this->set('students', $this->UsersService->getUserList(['mode' => 'list', 'filter' => ['role' => [3]]], true));
        $this->render('add_existing_student', 'ajax');
    }

    public function add_mentor($class_id)
    {
        $this->isAuthorizedAs(['Administrator', 'Account manager', 'School manager', 'School management']);

        if ($this->request->is('post') || $this->request->is('put')) {

            $result = $this->SchoolClassesService->addMentor($class_id, $this->request->data['Mentor']['mentor_id']);

            $this->formResponse(
                $result ? true : false,
                []
            );

            die;
        }

        $this->set('mentors', $this->UsersService->getUserList(['mode' => 'list', 'filter' => ['role' => [8, 1]]], true));
        $this->set('subjects', $this->SectionsService->getSectionSubjectList());
    }

    public function remove_mentor($class_id, $user_id)
    {
        $this->isAuthorizedAs(['Administrator', 'Account manager', 'School manager', 'School management']);

        if ($this->request->is('delete')) {
            $this->autoRender = false;
            $this->SchoolClassesService->removeFromClass($user_id, [
                'delete_mentor_school_class' => $this->SchoolClassesService->getClass($class_id)['id']
            ]);

            echo $this->formResponse(
                true,
                []
            );
        }

    }

    public function remove_manager($class_id, $user_id)
    {
        $this->isAuthorizedAs(['Administrator', 'Account manager', 'School manager', 'School management']);
        if ($this->request->is('delete')) {
            $this->autoRender = false;
            $this->SchoolClassesService->removeFromClass($user_id, [
                'delete_manager_school_class' => $this->SchoolClassesService->getClass($class_id)['id']
            ]);

            echo $this->formResponse(
                true,
                []
            );
        }
    }

    public function remove_teacher($teacher_id)
    {
        $this->isAuthorizedAs(['Administrator', 'Account manager', 'School manager', 'School management']);

        if ($this->request->is('delete')) {
            $this->autoRender = false;
            $this->SchoolClassesService->removeTeacher($teacher_id);

            echo $this->formResponse(
                true,
                []
            );
        }
    }

    public function remove_student($class_id, $user_id)
    {
        $this->isAuthorizedAs(['Administrator', 'Account manager', 'School manager', 'School management']);

        if ($this->request->is('delete')) {
            $this->autoRender = false;
            $this->SchoolClassesService->removeFromClass($user_id, [
                'delete_student_school_class' => $this->SchoolClassesService->getClass($class_id)['id']
            ]);

            echo $this->formResponse(
                true,
                []
            );
        }
    }

    public function delete($class_id)
    {
        $this->isAuthorizedAs(['Administrator', 'Account manager', 'School manager', 'School management']);

        if ($this->request->is('delete')) {
            $this->autoRender = false;
            $this->SchoolClassesService->deleteClass($class_id);
        }
    }

    public function delete_teacher($teacher_id)
    {
        $this->isAuthorizedAs(['Administrator', 'Account manager', 'School manager', 'School management']);

        if ($this->request->is('delete')) {
            $this->autoRender = false;
            $this->SchoolClassesService->deleteTeacher($teacher_id);
        }
    }

    public function edit_student($user_id)
    {
        $this->isAuthorizedAs(['Administrator', 'Account manager', 'School manager', 'School management']);

        if ($this->request->is('post') || $this->request->is('put')) {

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

    private function translateError($service)
    {
        if(!array_key_exists('error', $service->getErrors())){
            return $service->getErrors();
        }
        if(stristr($service->getErrors()['error'], 'School class id not found for class')){
            return (str_replace('School class id not found for class', 'SchoolKlas', $service->getErrors()['error']).' niet gevonden!');
        }

        return $service->getErrors()['error'];
    }

    private function getInitEducationLevelYears($education_levels, $uuid)
    {
        $maxYear = 1;
        foreach ($education_levels as $key => $education_level) {
            if ($education_level['uuid'] == $uuid) {
                $maxYear = $education_level['max_years'];
            }
        }
        $initEducationLevelYears = [];
        for ($i = 1; $i <= $maxYear; $i++) {
            $initEducationLevelYears[] = $i;
        }
        return $initEducationLevelYears;
    }
}