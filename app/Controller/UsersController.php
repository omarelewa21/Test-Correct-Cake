<?php

App::uses('AppController', 'Controller');
App::uses('UsersService', 'Lib/Services');
App::uses('SchoolClassesService', 'Lib/Services');
App::uses('SchoolLocationsService', 'Lib/Services');
App::uses('SchoolYearsService', 'Lib/Services');
App::uses('SchoolsService', 'Lib/Services');
App::uses('UmbrellaOrganisationsService', 'Lib/Services');

/**
 * Users controller
 *
 */
class UsersController extends AppController {

    /**
     * Called before each action.
     *
     * @return void
     */
    public function beforeFilter()
    {
        $this->Auth->allowedActions = array('login', 'status', 'forgot_password', 'reset_password');

        $this->UsersService = new UsersService();
        $this->SchoolClassesService = new SchoolClassesService();
        $this->SchoolLocationsService = new SchoolLocationsService();
        $this->SchoolYearsService = new SchoolYearsService();
        $this->SchoolsService = new SchoolsService();
        $this->UmbrellaOrganisationsService = new UmbrellaOrganisationsService();

        parent::beforeFilter();
    }

    public function login()
    {
        if ($this->request->is('post') || $this->request->is('put')) {

            $appType = $this->request->data['appType'];
            
            if($this->Session->check('TLCHeader') && $this->Session->read('TLCHeader') !== 'not secure...' ) {
                if( !strpos($this->Session->read('TLCVersion'), '|') || $this->Session->read('TLCVersion') === 'x'){
                    $message = 'Uw versie van de app wordt niet meer ondersteund. Download de nieuwe versie via http://www.test-correct.nl';
                    $this->formResponse(false,['message' => $message]);
                    exit();
                }else{
                    if(explode('|',$this->Session->read('TLCVersion'))[1] !== '2.0'){
                        $message = 'Uw versie van de app wordt niet meer ondersteund. Download de nieuwe versie via http://www.test-correct.nl';
                        $this->formResponse(false,['message' => $message]);
                        exit();
                    }
                }
            // }else{
                // if($appType === 'ipad') {
                //     $message = 'Uw versie van de app wordt niet meer ondersteund. Download de nieuwe versie via http://www.test-correct.nl';
                //     $this->formResponse(false,['message' => $message]);
                //     exit();
                // }
            }

          if ($this->Auth->login()) {
              $this->formResponse(true, array('data' => AuthComponent::user(), 'message' => $message));
          } else {
              $this->formResponse(false);
          }
        }
    }

    public function logout()
    {
        $this->autoRender = false;
        $this->Auth->logout();
    }

    public function forgot_password() {
        $this->autoRender = false;
        $email = $this->request->data['email'];

        $response = $this->UsersService->sendRequest($email);

        echo $response;
    }

    public function reset_password($token) {

        if($this->request->is('post')) {

            $data = $this->request->data;

            $result = $this->UsersService->resetPassword($token, $data);

            $this->set('result', $result);
        }
    }

    public function status() {
        $this->autoRender = false;

        echo $this->Auth->loggedIn() ? 1 : 0;
    }

    public function welcome() {
        $roles = AuthComponent::user('roles');

        $menus = array();

        $view = "welcome";

        foreach($roles as $role) {
            if($role['name'] == 'Teacher') {
                $view = "welcome_teacher";
            }

            if($role['name'] == 'Student') {
                $view = "welcome_student";
            }
        }

        $this->render($view, 'ajax');
    }

    public function index($type) {

        switch($type) {
            case 'accountmanagers':
                $params = [
                    'title' => 'Accountmanagers',
                    'add_title' => 'Nieuwe Accountmanager'
                ];
                $this->set('sales_organisations', $this->UsersService->getSalesOrganisations());
                break;

            case 'managers':
                $params = [
                    'title' => 'Schoolbeheerder',
                    'add_title' => 'Nieuwe Schoolbeheerder'
                ];
                break;

            case 'students':

                $params = [
                    'title' => 'Studenten',
                    'add_title' => 'Nieuwe student'
                ];

                $school_locations[0] = 'Alle';
                $school_locations += $this->SchoolLocationsService->getSchoolLocationList();

                $this->set('school_location', $school_locations);
                break;

            case 'teachers':
                $params = [
                    'title' => 'Docenten',
                    'add_title' => 'Nieuwe Docent'
                ];
                break;

            case 'management':
                $params = [
                    'title' => 'Directieleden',
                    'add_title' => 'Nieuw Directielid'
                ];
                break;
        }

        $roles = AuthComponent::user('roles');
        $this->set('role', $roles['0']['name']);
        $this->set('params', $params);
        $this->set('type', $type);
    }

    public function profile_picture($user_id) {
        $this->autoRender = false;
        $user = $this->UsersService->getUser($user_id);

        if(empty($user['profile_image_size'])) {
            $result = file_get_contents(APP . WEBROOT_DIR.'/img/ico/user.png');
            $this->response->type('image/png');
        }else{
            $result = $this->UsersService->getProfilePicture($user_id);
            $this->response->type($user['profile_image_mime_type']);
        }
        $this->response->body($result);

        return $this->response;
    }

    public function edit($user_id) {

        if($this->request->is('post') || $this->request->is('put')) {

            $data = $this->request->data['User'];

            $result = $this->UsersService->updateUser($user_id, $data);

            if($this->Session->check('user_profile_picture')) {
                $result = $this->UsersService->updateProfilePicture($user_id, $this->Session->read('user_profile_picture'));


                $this->Session->delete('user_profile_picture');
            }

            $this->formResponse(
                $result ? true : false,
                []
            );

            die;
        }

        $user = $this->UsersService->getUser($user_id);
        $user['User'] = $user;

        $this->request->data = $user;

        switch($user['roles'][0]['id']) {

            case 6: //Managers
                $this->set('school_locations', $this->SchoolLocationsService->getSchoolLocationList());
                $this->set('schools', $this->SchoolsService->getSchoolList());
                $this->render('edit_managers', 'ajax');
                break;

            case 5: //Accountmanager
                $this->set('sales_organisations', $this->UsersService->getSalesOrganisations());
                $this->render('edit_accountmanagers', 'ajax');
                break;

            case 1: //Teachter
                $this->render('edit_teachers', 'ajax');
                break;

            case 7: //Management
                $this->render('edit_management', 'ajax');
                break;

            case 9: //Parent
                $this->render('edit_parents', 'ajax');
                break;

            case 3: //Student

                $activeClasses = [];

                foreach($user['User']['student_school_classes'] as $class) {
                    $activeClasses[] = $class['id'];
                }

                $this->set('active_classes', $activeClasses);

                $this->set('school_classes', $this->SchoolClassesService->getClassesList());
                $this->render('edit_students', 'ajax');
                break;
        }

        $this->Session->delete('user_profile_picture');
    }

    public function notify_welcome($type) {

        if($type == 'students') {
            $role_id = 3;
        }elseif($type == 'teachers') {
            $role_id = 1;
        }elseif($type == 'management') {
            $role_id = 7;
        }else{
            die;
        }

        $this->autoRender = false;
        debug($this->UsersService->notifyWelcome($role_id));
    }

    public function view($user_id) {

        $user = $this->UsersService->getUser($user_id);

        $this->set('user', $user);

        switch($user['roles'][0]['id']) {

            case 1: //Teachters
                $this->set('school_years', $this->SchoolYearsService->getSchoolYearList());
                $this->render('view_teacher', 'ajax');
                break;

            case 3: //Student
                $parents = $this->UsersService->getParents($user_id);

                $this->set('parents', $parents);
                $this->render('view_student', 'ajax');
                break;

            case 6: //Managers
                $this->render('edit_managers', 'ajax');
                break;

            case 5: //Accountmanager
                $this->render('view_accountmanager', 'ajax');
                break;
        }

    }

    public function move($user_id) {

        if($this->request->is('post') || $this->request->is('put')) {
            $params = [
                'school_location_id' => $this->request->data['User']['school_location_id'],
                'username' => $this->request->data['User']['email'],
                'external_id' => $this->request->data['User']['external_id']
            ];

            $result = $this->UsersService->move($user_id, $params);

            $this->formResponse(
                $result ? true : false,
                []
            );

            die;
        }

        $this->set('school_locations', $this->SchoolLocationsService->getSchoolLocationList());
    }

    public function load($type) {

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

        switch($type) {
            case 'accountmanagers':
                $params['filter']['role'] = 5;
                break;

            case 'managers':
                $params['filter']['role'] = 6;
                break;

            case 'teachers':
                $params['filter']['role'] = 1;
                break;

            case 'students':
                $params['filter']['role'] = 3;
                $params['with'] = ['school_location', 'studentSchoolClasses'];
                break;

            case 'management':
                $params['filter']['role'] = 7;
                break;
        }

        $params['order']['name'] = 'asc';
        $users = $this->UsersService->getUsers($params);

        $roles = AuthComponent::user('roles');
        $this->set('role', $roles['0']['name']);
        $this->set('users', $users);
        $this->set('type', $type);
        $this->render('load_' . $type, 'ajax');
    }

    public function profile_picture_upload() {
        $this->autoRender = false;

        $data = $this->request->data;

        if(!in_array($data['User']['file']['type'], ['image/jpg', 'image/jpeg', 'image/png'])) {
            echo '<script>window.parent.Notify.notify("Foutief bestandsformaat", "error", 3000); window.parent.Loading.hide();</script>';
            die;
        }

        if(isset($data['User']['file']['name']) && !empty($data['User']['file']['name'])) {
            $file = new File($data['User']['file']['tmp_name']);
            $tmpFile = TMP . time();
            $file->copy($tmpFile);

            $this->Session->write('user_profile_picture', $tmpFile);
        }

        echo '<script>window.parent.Loading.hide();</script>';
    }

    public function add($type, $parameter1 = null, $parameter2 = null) {

        if($this->request->is('post') || $this->request->is('put')) {
            $data = $this->request->data['User'];

            if($type == 'teachers') {
                $data['user_roles'] = [1];

                if(!isset($data['school_location_id'])) {
                    $data['school_location_id'] = AuthComponent::user()['school_location_id'];
                }
            }

            if($type == 'students') {
                $data['user_roles'] = [3];
            }

            if($type == 'parents') {
                $data['user_roles'] = [9];
                $data['student_parents_of'] = [$this->Session->read('owner')];
            }

            if($type == 'management') {
                $data['user_roles'] = [7];

                if($this->Session->check('class_id')) {
                    $data['manager_school_classes'] = [$this->Session->read('class_id')];
                }

                if(!isset($data['school_location_id'])) {
                    $data['school_location_id'] = AuthComponent::user()['school_location_id'];
                }
            }

            if($type == 'managers') {
                if(!isset($data['school_location_id']) && !isset($data['school_id'])) {
                    $data['school_location_id'] = AuthComponent::user()['school_location_id'];
                }

                $data['user_roles'] = [6];
            }

            if($type == 'mentors') {
                if($this->Session->check('class_id')) {
                    $data['mentor_school_classes'] = [$this->Session->read('class_id')];
                }
                $data['user_roles'] = [8];
            }


            $result = $this->UsersService->addUser($type, $data);

            if($this->Session->check('user_profile_picture')) {
                $this->UsersService->updateProfilePicture($result['id'], $this->Session->read('user_profile_picture'));
                $this->Session->delete('user_profile_picture');
            }

            if(isset($result['id'])) {
                $this->formResponse(
                    true,
                    [
                        'id' => $result['id']
                    ]
                );
            }elseif($result == 'external_code') {
                $this->formResponse(
                    false,
                    [
                        'error' => 'external_code'
                    ]
                );
            }elseif($result == 'username') {
                $this->formResponse(
                    false,
                    [
                        'error' => 'username'
                    ]
                );
            }else{
                $this->formResponse(
                    false,
                    []
                );
            }

            die;
        }

        if($type == 'teachers') {
            $this->set('school_locations', $this->SchoolLocationsService->getSchoolLocationList());
        }

        if($type == 'managers' || $type == 'mentors') {
            $this->set('school_locations', $this->SchoolLocationsService->getSchoolLocationList());
            $this->set('schools', $this->SchoolsService->getSchoolList());
        }

        if($type == 'students') {
            $this->set('school_classes', $this->SchoolClassesService->getClassesList());
            $this->set('school_locations', $this->SchoolLocationsService->getSchoolLocationList());
            $this->set('class_id', $parameter1);
        }

        if($type == 'accountmanagers') {
            $this->set('sales_organisations', $this->UsersService->getSalesOrganisations());
        }

        if($type == 'parents') {
            $this->Session->write('owner', $parameter1);
        }

        $this->Session->delete('user_profile_picture');

        $this->set('parameter1', $parameter1);
        $this->set('parameter2', $parameter2);

        $this->render('add_' . $type, 'ajax');
    }

    public function delete($user_id) {
        $this->autoRender = false;

        $this->formResponse(
            $this->UsersService->deleteUser($user_id) ? true : false,
            []
        );
    }

    public function menu() {
        $roles = AuthComponent::user('roles');

        $menus = array();

        foreach($roles as $role) {

            if($role['name'] == 'Administrator') {
                $menus['accountmanagers'] = "Accountmanagers";
                $menus['lists'] = "Database";
            }

            if($role['name'] == 'Account manager') {
                $menus['lists'] = "Database";
            }

            if($role['name'] == 'School manager') {
                $menus['users'] = "Gebruikers";
                $menus['lists'] = "Database";
                $menus['analyses'] = "Analyses";
            }

            if($role['name'] == 'Teacher') {
                $menus['library'] = "Itembank";
                $menus['tests'] = "Toetsing";
                $menus['analyses'] = "Analyses";
                $menus['messages'] = "Berichten";
            }

            if($role['name'] == 'Student') {
                $menus['tests'] = "Toetsen";
                $menus['analyses'] = "Analyse";
                $menus['messages'] = "Berichten";
            }

            if($role['name'] == 'School management') {
                $menus['analyses'] = "Analyse";
                $menus['messages'] = "Berichten";
            }
        }

        $this->set('menus', $menus);
    }

    public function tiles() {
        $roles = AuthComponent::user('roles');

        $tiles = array();

        foreach($roles as $role) {

            if($role['name'] == 'Administrator') {
                $tiles['users_accountmanagers'] = array(
                    'menu' => 'accountmanagers',
                    'icon' => 'testlist',
                    'title' => 'Gebruikers',
                    'path' => '/users/index/accountmanagers'
                );

                $tiles['umbrella_organisations'] = array(
                    'menu' => 'lists',
                    'icon' => 'testlist',
                    'title' => 'Koepelorganisaties',
                    'path' => '/umbrella_organisations'
                );

                $tiles['schools'] = array(
                    'menu' => 'lists',
                    'icon' => 'testlist',
                    'title' => 'Scholengemeenschap',
                    'path' => '/schools'
                );

                $tiles['school_locations'] = array(
                    'menu' => 'lists',
                    'icon' => 'testlist',
                    'title' => 'Schoollocaties',
                    'path' => '/school_locations'
                );
                $tiles['students'] = array(
                    'menu' => 'lists',
                    'icon' => 'testlist',
                    'title' => 'Studenten',
                    'path' => '/users/index/students'
                );
            }

            if($role['name'] == 'Account manager') {
                $tiles['users_administrators'] = array(
                    'menu' => 'users',
                    'icon' => 'testlist',
                    'title' => 'Schoolbeheerder',
                    'path' => '/users/index/managers'
                );

                $tiles['umbrella_organisations'] = array(
                    'menu' => 'lists',
                    'icon' => 'testlist',
                    'title' => 'Koepelorganisaties',
                    'path' => '/umbrella_organisations'
                );

                $tiles['schools'] = array(
                    'menu' => 'lists',
                    'icon' => 'testlist',
                    'title' => 'Scholengemeenschap',
                    'path' => '/schools'
                );

                $tiles['school_locations'] = array(
                    'menu' => 'lists',
                    'icon' => 'testlist',
                    'title' => 'Schoollocaties',
                    'path' => '/school_locations'
                );
            }

            if($role['name'] == 'School manager') {
                $tiles['users_teachers'] = array(
                    'menu' => 'users',
                    'icon' => 'testlist',
                    'title' => 'Docenten',
                    'path' => '/users/index/teachers'
                );

                $tiles['users_management'] = array(
                    'menu' => 'users',
                    'icon' => 'testlist',
                    'title' => 'Directieleden',
                    'path' => '/users/index/management'
                );

                $tiles['school_years'] = array(
                    'menu' => 'lists',
                    'icon' => 'testlist',
                    'title' => 'Schooljaren',
                    'path' => '/school_years'
                );

                $tiles['sections'] = array(
                    'menu' => 'lists',
                    'icon' => 'testlist',
                    'title' => 'Secties',
                    'path' => '/sections'
                );

                $tiles['school_class'] = array(
                    'menu' => 'lists',
                    'icon' => 'testlist',
                    'title' => 'Klassen',
                    'path' => '/school_classes'
                );

                $tiles['students'] = array(
                    'menu' => 'users',
                    'icon' => 'testlist',
                    'title' => 'Studenten',
                    'path' => '/users/index/students'
                );

                $tiles['classes_analyses'] = array(
                    'menu' => 'analyses',
                    'icon' => 'analyse-klassen',
                    'title' => 'Klassen',
                    'path' => '/analyses/school_classes_overview'
                );

                $tiles['student_analyses'] = array(
                    'menu' => 'analyses',
                    'icon' => 'analyse-leerlingen',
                    'title' => 'Studenten',
                    'path' => '/analyses/students_overview'
                );
            }

            if($role['name'] == 'Teacher') {
                $tiles['tests_overview'] = array(
                    'menu' => 'library',
                    'icon' => 'testlist',
                    'title' => 'Toetsen',
                    'path' => '/tests/index'
                );

                $tiles['questions_overview'] = array(
                    'menu' => 'library',
                    'icon' => 'questionlist',
                    'title' => 'Vragen',
                    'path' => '/questions/index'
                );

                $tiles['tests_planned'] = array(
                    'menu' => 'tests',
                    'icon' => 'gepland',
                    'title' => 'Geplande toetsen',
                    'path' => '/test_takes/planned_teacher'
                );

                $tiles['tests_surveillance'] = array(
                    'menu' => 'tests',
                    'icon' => 'surveilleren',
                    'title' => 'Surveilleren',
                    'path' => '/test_takes/surveillance'
                );

                $tiles['tests_taken'] = array(
                    'menu' => 'tests',
                    'icon' => 'afgenomen',
                    'title' => 'Afgenomen',
                    'path' => '/test_takes/taken_teacher'
                );

                $tiles['tests_discussed'] = array(
                    'menu' => 'tests',
                    'icon' => 'bespreken',
                    'title' => 'Bespreken',
                    'path' => '/test_takes/discussion'
                );

                $tiles['tests_examine'] = array(
                    'menu' => 'tests',
                    'icon' => 'nakijken',
                    'title' => 'Nakijken',
                    'path' => '/test_takes/to_rate'
                );

                $tiles['tests_graded'] = array(
                    'menu' => 'tests',
                    'icon' => 'becijferd',
                    'title' => 'Becijferd',
                    'path' => '/test_takes/rated'
                );

                $tiles['analyse'] = array(
                    'menu' => 'analyses',
                    'icon' => 'analyse-leraar',
                    'title' => 'Uw analyse',
                    'path' => '/analyses/teacher/' . AuthComponent::user('id')
                );

                $tiles['analyse_student'] = array(
                    'menu' => 'analyses',
                    'icon' => 'analyse-leerlingen',
                    'title' => 'Studenten',
                    'path' => '/analyses/students_overview'
                );

                $tiles['analyse_classes'] = array(
                    'menu' => 'analyses',
                    'icon' => 'analyse-klassen',
                    'title' => 'Klassen',
                    'path' => '/analyses/school_classes_overview'
                );

                $tiles['messages'] = array(
                    'menu' => 'messages',
                    'icon' => 'messages',
                    'title' => 'Berichten',
                    'path' => '/messages'
                );
            }

            if($role['name'] == 'Student') {
                $tiles['tests_planned'] = array(
                    'menu' => 'tests',
                    'icon' => 'gepland',
                    'title' => 'Geplande toetsen',
                    'path' => '/test_takes/planned_student'
                );
                $tiles['tests_discussed'] = array(
                    'menu' => 'tests',
                    'icon' => 'bespreken',
                    'title' => 'Te bespreken',
                    'path' => '/test_takes/taken_student'
                );

                $tiles['tests_glance'] = array(
                    'menu' => 'tests',
                    'icon' => 'inzien',
                    'title' => 'Inzien',
                    'path' => '/test_takes/discussed_glance'
                );

                $tiles['tests_rated'] = array(
                    'menu' => 'tests',
                    'icon' => 'becijferd',
                    'title' => 'Becijferd',
                    'path' => '/test_takes/rated_student'
                );

                $tiles['analyses_student'] = array(
                    'menu' => 'analyses',
                    'icon' => 'analyse-leerling',
                    'title' => 'Jouw analyse',
                    'path' => '/analyses/student/' . AuthComponent::user('id')
                );

                $tiles['messages'] = array(
                    'menu' => 'messages',
                    'icon' => 'messages',
                    'title' => 'Berichten',
                    'path' => '/messages'
                );
            }

            if($role['name'] == 'School management') {
                $tiles['classes_analyses'] = array(
                    'menu' => 'analyses',
                    'icon' => 'analyse-klassen',
                    'title' => 'Klassen',
                    'path' => '/analyses/school_classes_overview'
                );

                $tiles['teachers'] = array(
                    'menu' => 'analyses',
                    'icon' => 'analyse-leraar',
                    'title' => 'Docenten',
                    'path' => '/analyses/teachers_overview'
                );
                $tiles['students'] = array(
                    'menu' => 'analyses',
                    'icon' => 'analyse-leerlingen',
                    'title' => 'Studenten',
                    'path' => '/analyses/students_overview'
                );

                $tiles['messages'] = array(
                    'menu' => 'messages',
                    'icon' => 'messages',
                    'title' => 'Berichten',
                    'path' => '/messages'
                );
            }
        }

        $this->set('tiles', $tiles);
    }

    public function password_reset() {
        if($this->request->is('post')) {

            $this->autoRender = false;

            $data = $this->request->data['User'];

            if($data['password'] != $data['password_new']) {
                $this->formResponse(false, [
                    'message' => 'Wachtwoorden komen niet overeen'
                ]);

                die;
            }

            $user_id = AuthComponent::user('id');

            $result = $this->UsersService->resetPasswordForm($user_id, $data);


            if($result != '{"old_password":["Record does not match stored value"]}') {
                $this->formResponse(true);
            }else{
                $this->formResponse(false, [
                    'message' => 'Wachtwoorden komen niet overeen'
                ]);
            }
        }
    }

    public function info() {
        $this->autoRender = false;
        $info = AuthComponent::user();

        $student = false;

        foreach($info['roles'] as $role) {
            if($role['name'] == 'Student') {
                $student = true;
            }
        }

        if(empty($info['name_suffix'])) {
            $info['name_suffix'] = "";
        }

        if(!$student) {
            $info['name_first'] = substr($info['name_first'], 0, 1) . '.';
        }

        echo json_encode($info);
    }
}
