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
class UsersController extends AppController
{

    /**
     * Called before each action.
     *
     * @return void
     */
    public function beforeFilter()
    {
        $this->Auth->allowedActions = array('login', 'status', 'forgot_password', 'reset_password', 'register_new_teacher', 'register_new_teacher_successful','registereduix');

        $this->UsersService = new UsersService();
        $this->SchoolClassesService = new SchoolClassesService();
        $this->SchoolLocationsService = new SchoolLocationsService();
        $this->SchoolYearsService = new SchoolYearsService();
        $this->SchoolsService = new SchoolsService();
        $this->UmbrellaOrganisationsService = new UmbrellaOrganisationsService();

        parent::beforeFilter();
    }

    public function registereduix()
    {
        if ($this->request->is('post')) {
            $result = $this->UsersService->addUserEduIx(
                $this->params['url']['ean'],
                $this->params['url']['edurouteSessieID'],
                $this->params['url']['signature'],
                $this->request->data['User']
            );
            $response = json_decode($result);
            if (property_exists($response, 'errors') && count((array)$response->errors) > 0) {
                $this->formResponse(false, ['message' => $response->message]);
            } else {
                $this->formResponse(true, ['data' => $response]);
            }
            exit();
        }

        $response = $this->UsersService->registerEduIx(
            $this->params['url']['ean'],
            $this->params['url']['edurouteSessieID'],
            $this->params['url']['signature']
        );

        $user = new stdClass;
        $user->school_location = $response['eduProfile']['homeOrganization'];
        $user->name_first = $response['eduProfile']['givenName'];
        $user->name_suffix = $response['ediProfile']['personTussenvoegsels'];
        $user->name = $response['eduProfile']['sn'];
        $user->username = $response['eduProfile']['personRealID'];


//        var_dump($response);die;

        $this->set('user', $user);
    }

    public function login()
    {
//        if($this->Session->check('AppTooOld') && $this->Session->read('AppTooOld') === true){
//            if(strtolower($this->Session->read('AppOS')) === 'windows') {
//                $view = "windows_update";
//                echo $this->render($view,'ajax');
//                exit;
//            }
//        }

        ## MarkO: Ik snap nog niet precies wanneer ik in deze methode uit kom. Maar $message hieronder was nog niet gezet en
        ## dat gaf een crash op de test/dev portals (niet op live) dus ik heb een default gezet zonder goed te weten wat het doet.
        $message = "";
        ## Einde bericht.

        if ($this->request->is('post') || $this->request->is('put')) {
            $appType = $this->request->data['appType'];

            if ($this->Auth->login()) {
                //              $this->formResponse(true, array('data' => AuthComponent::user(), 'message' => $message));
                if ($this->Session->check('TLCHeader')){// && $this->Session->read('TLCHeader') !== 'not secure...') {
                    if ($this->UsersService->hasRole('student')) {
                        $versionCheckResult = $this->Session->check('TLCVersionCheckResult') ? $this->Session->read('TLCVersionCheckResult') : 'NOTALLOWED';
                        $data = [
                            'os' => $this->Session->check('TLCOs') ? $this->Session->read('TLCOs') : 'unknown',
                            'version' => $this->Session->check('TLCVersion') ? $this->Session->read('TLCVersion') : 'unknown',
                            'version_check_result' => $versionCheckResult,
                            'headers' => $this->Session->check('headers') ? json_encode($this->Session->read('headers')) : 'unknown',
                        ];

                        $this->UsersService->storeAppVersionInfo($data, AuthComponent::user('id'));

                        if ($versionCheckResult === 'NOTALLOWED') {
                            // somebody should be logedout, but we don't doe this yet
//                            $this->Auth->logout();
//                            $this->Session->destroy();
//                            $message = 'Uw versie van de app wordt niet meer ondersteund. Download de nieuwe versie via http://www.test-correct.nl';
//                            $this->formResponse(false, ['message' => $message]);
//                            exit();
                        }
                    }
                }

                // no need to expose user info
                $this->formResponse(true, array('message' => $message));
            } else {
                $this->formResponse(false);
            }

//            if ($this->Session->check('TLCHeader') && $this->Session->read('TLCHeader') !== 'not secure...') {
//                if (!strpos($this->Session->read('TLCVersion'), '|') || $this->Session->read('TLCVersion') === 'x') {
//                    $message = 'Uw versie van de app wordt niet meer ondersteund. Download de nieuwe versie via http://www.test-correct.nl';
//                    $this->formResponse(false, ['message' => $message]);
//                    exit();
//                } else {
//                    $version = explode('|', $this->Session->read('TLCVersion'))[1];
//                    if (!in_array($version, ['2.0', '2.1', '2.1', '2.2', '2.3', '2.4', '2.5', '2.6', '2.7', '2.8', '2.9'])) {
//                        $message = 'Uw versie van de app wordt niet meer ondersteund. Download de nieuwe versie via http://www.test-correct.nl';
//                        $this->formResponse(false, ['message' => $message]);
//                        exit();
//                    }
//                }
//                // }else{
//                // if($appType === 'ipad') {
//                //     $message = 'Uw versie van de app wordt niet meer ondersteund. Download de nieuwe versie via http://www.test-correct.nl';
//                //     $this->formResponse(false,['message' => $message]);
//                //     exit();
//                // }
//            }


        }
    }

    public function edit_register_new_teacher($userId)
    {
        if ($this->request->is('post')) {
            $response = $this->UsersService->updateRegisteredNewTeacher(
                $this->request->data['User'],
                $userId
            );
            $result = (json_decode($response));

            if (property_exists($result, 'errors') && count((array)$result->errors) > 0) {
                $this->formResponse(false, $result);
            } else {
                $this->formResponse(true, ['data' => $response]);
            }
            exit();
        }
        $data = $this->UsersService->getRegisteredNewTeacherByUserId($userId);
        $this->set('user', (object)$data);
    }

    public function register_new_teacher()
    {
        if ($this->request->is('post')) {
            $response = $this->UsersService->registerNewTeacher(
                $this->request->data['User']
            );
            $result = (json_decode($response));

            if (property_exists($result, 'errors') && count((array)$result->errors) > 0) {
                $this->formResponse(false, $result);
            } else {
                $this->formResponse(true, ['data' => $response]);
            }
            exit();
        }
    }

    public function register_new_teacher_successful()
    {

    }

    protected function getSessionHeaderData()
    {
        $ar = ['TLCHeader','TLCOs','TLCVersion','TLCVersionCheckResult','headers'];
        $allAvailable = true;
        $returnAr = [];
        foreach($ar as $item){
            if($this->Session->check($item)){
                $returnAr[$item] = $this->Session->read($item);
            } else {$allAvailable = false;}
        }

        if($allAvailable === false){
            return null;
        }
        return $returnAr;
    }

    protected function reinitFromSessionHeaderData($data)
    {
        foreach($data as $key => $val){
            $this->Session->write($key,$val);
        }
    }

    public function logout()
    {
        $this->autoRender = false;
        $this->Auth->logout();
        $tlcSessionHeaderData = $this->getSessionHeaderData();
        $this->Session->destroy();
        if($tlcSessionHeaderData !== null && is_array($tlcSessionHeaderData)){
            $this->Session->renew();
            $this->reinitFromSessionHeaderData($tlcSessionHeaderData);
        }
    }

    public function forgot_password()
    {
        $this->autoRender = false;
        $email = $this->request->data['email'];

        $response = $this->UsersService->sendRequest($email);

        echo $response;
    }

    public function reset_password($token)
    {
        if ($this->request->is('post')) {
            $data = $this->request->data;

            $result = $this->UsersService->resetPassword($token, $data);

            $this->set('result', $result);
        }
    }

    public function status()
    {
        $this->autoRender = false;

        echo $this->Auth->loggedIn() ? 1 : 0;
    }

    public function store_onboarding_wizard_step()
    {
        if ($this->request->is('post')) {
            $data = $this->request->data;

            $result = $this->UsersService->storeOnboardingWizardStep($data);

            return $this->formResponse(
                $result ? true : false,
                ['progress' => $result['progress']]
            );
        }
        return $this->formResponse(
            false, []
        );
    }

    public function onboarding_wizard()
    {
        if ($this->request->is('put')) {
            $data = $this->request->data;

            $result = $this->UsersService->updateOnboardingWizard($data);

            return $this->formResponse(
                $result ? true : false, []
            );
        }
        return $this->formResponse(
            false, []
        );
    }

    public function onboarding_wizard_report()
    {
//            $this->ifNotAllowedExit(['Account manager'], true);

            $result = $this->UsersService->createOnboardingWizardReport($this->request->data);

            if(!$result) exit;

            $this->response->body($result);
            $this->response->header('Content-Disposition', 'attachment; filename=marketing_report_'.date('YmdHi').'.xls');
            return $this->response;
    }

    public function welcome()
    {
        $roles = AuthComponent::user('roles');

        $menus = array();

        $view = "welcome";

        foreach ($roles as $role) {
            if ($role['name'] == 'Teacher') {
                $view = "welcome_teacher";
                $wizardSteps = $this->UsersService->getOnboardingWizard(AuthComponent::user('id'));

                $this->set('wizard_steps', $wizardSteps);
                $this->set('progress', floor($wizardSteps['count_sub_steps_done'] / $wizardSteps['count_sub_steps'] * 100));
            }

            if ($role['name'] == 'Student') {
                $view = "welcome_student";
//                if($this->Session->check('AppTooOld') && $this->Session->read('AppTooOld') === true){
//                    if($this->check('AppOS') && $this->read('AppOS') === 'windows') {
//                        $view = "welcome_student_update";
//                    }
//                }
            }
        }

        $this->render($view, 'ajax');
    }

    public function index($type)
    {
        $this->isAuthorizedAs(['Administrator', 'Account manager', 'School manager', 'School management']);

        switch ($type) {
            case 'accountmanagers':
                $params = [
                    'title'     => 'Accountmanagers',
                    'add_title' => 'Nieuwe Accountmanager'
                ];
                $this->set('sales_organisations', $this->UsersService->getSalesOrganisations());
                break;

            case 'managers':
                $params = [
                    'title'     => 'Schoolbeheerder',
                    'add_title' => 'Nieuwe Schoolbeheerder'
                ];
                break;

            case 'students':

                $params = [
                    'title'     => 'Studenten',
                    'add_title' => 'Nieuwe student'
                ];

                $school_locations[0] = 'Alle';
                $school_locations += $this->SchoolLocationsService->getSchoolLocationList();

                $this->set('school_location', $school_locations);
                break;

            case 'teachers':
                $params = [
                    'title'     => 'Docenten',
                    'add_title' => 'Nieuwe Docent'
                ];
                $school_locations = [0 => 'Alle' ];
                $school_locations += $this->SchoolLocationsService->getSchoolLocationList();

                $this->set('school_location', $school_locations);
                break;

            case 'management':
                $params = [
                    'title'     => 'Directieleden',
                    'add_title' => 'Nieuw Directielid'
                ];
                break;
        }

        $roles = AuthComponent::user('roles');
        $this->set('role', $roles['0']['name']);
        $this->set('params', $params);
        $this->set('type', $type);
    }

    public function profile_picture($user_id)
    {
        $this->autoRender = false;

        // if in local test mode, don't do a user call, but just show the default icon
        if (substr(Router::fullBaseUrl(), -5) === '.test' || substr(Router::fullBaseUrl(), -7) === '.test/#') {
            $result = file_get_contents(APP . WEBROOT_DIR . '/img/ico/user.png');
            $this->response->type('image/png');
            $this->response->body($result);
            return $this->response;
        }

        $user = $this->UsersService->getUser($user_id);

        if (empty($user['profile_image_size'])) {
            $result = file_get_contents(APP . WEBROOT_DIR . '/img/ico/user.png');
            $this->response->type('image/png');
        } else {
            $result = $this->UsersService->getProfilePicture($user_id);
            $this->response->type($user['profile_image_mime_type']);
        }
        $this->response->body($result);

        return $this->response;
    }

    public function change_password_for_user($user_id, $class_id)
    {
        $this->isAuthorizedAs(['Teacher']);

        if ($this->request->is('post') || $this->request->is('put')) {
            $data = $this->request->data['User'];
            $data['class_id'] = $class_id;

            if (strlen(trim($data['password'])) < 1) {
                $this->formResponse(false, ['error' => 'Er dient een wachtwoord opgegven te worden']);
            } elseif ($data['password'] !== $data['password_confirmation']) {
                $this->formResponse(false, ['error' => 'De wachtwoorden komen niet overeen']);
            } else {
                $result = $this->UsersService->updatePasswordForUser($user_id, $data);

                $this->formResponse(
                    $result ? true : false,
                    []
                );
            }

            die;
        }

        $user = $this->UsersService->getUser($user_id);
        $this->set('user', $user);
        $this->set('class_id', $class_id);
    }

    public function edit($user_id)
    {
        $this->isAuthorizedAs(['Administrator', 'Account manager', 'School manager', 'School management']);

        if ($this->request->is('post') || $this->request->is('put')) {
            $data = $this->request->data['User'];

            $result = $this->UsersService->updateUser($user_id, $data);

            if ($this->Session->check('user_profile_picture')) {
                $result = $this->UsersService->updateProfilePicture($user_id, $this->Session->read('user_profile_picture'));


                $this->Session->delete('user_profile_picture');
            }

            //TCP-125
            //if it is not an array, it is an error
            if (!is_array($result)) {
                $response = "De gebruiker kon niet worden bijgewerkt";

                //try to decode the error (JSON to array)
                //or fail and show a general error
                try {
                    $error = json_decode($result, true);

                    if(isset($error['errors']['username'])) {
                        $response = "Dit e-mailadres is al in gebruik";
                    }

                } catch (\Throwable $th) {}

                $this->formResponse(
                    false,
                    [$response]
                );

                die;
            }

            $this->formResponse(
                true,
                []
            );

            die;
        }

        $user = $this->UsersService->getUser($user_id);
        $user['User'] = $user;

        $this->request->data = $user;

        switch ($user['roles'][0]['id']) {

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

                foreach ($user['User']['student_school_classes'] as $class) {
                    $activeClasses[] = $class['id'];
                }

                $this->set('active_classes', $activeClasses);

                $this->set('school_classes', $this->SchoolClassesService->getClassesList());
                $this->render('edit_students', 'ajax');
                break;
        }

        $this->Session->delete('user_profile_picture');
    }

    public function notify_welcome($type)
    {
        $this->isAuthorizedAs(['Administrator', 'Account manager', 'School manager', 'School management']);

        if ($type == 'students') {
            $role_id = 3;
        } elseif ($type == 'teachers') {
            $role_id = 1;
        } elseif ($type == 'management') {
            $role_id = 7;
        } else {
            die;
        }

        $this->autoRender = false;
        debug($this->UsersService->notifyWelcome($role_id));
    }

    public function view($user_id)
    {
        $user = $this->UsersService->getUser($user_id);

        $this->set('user', $user);

        switch ($user['roles'][0]['id']) {

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

    public function switch_school_location($userId)
    {
        $this->isAuthorizedAs(['Administrator']);
        $user = $this->UsersService->getUser($userId);
        if ($this->request->is('post') || $this->request->is('put')) {
            if ($user->school_location_id == $this->request->data['User']['school_location_id']) {
                // we don't need to do anything, same school location, so done already
                $this->formResponse(
                    true,
                    []
                );
                die;
            }

            $params = [
                'school_location_id' => $this->request->data['User']['school_location_id'],
            ];

            $result = $this->UsersService->switch_school_location($userId, $params);

            if ($result === false) {

                $this->formResponse(
                    false,
                    $this->UsersService->getErrors()
                );
            } else {
                $this->formResponse(
                    true,
                    []
                );
            }

            die;
        }

        $this->set('school_locations', $this->SchoolLocationsService->getSchoolLocationList());

        $this->set('user', $user);
    }

    public function move($user_id)
    {
        $this->isAuthorizedAs(['Administrator', 'Account manager', 'School manager', 'School management']);

        if ($this->request->is('post') || $this->request->is('put')) {
            $params = [
                'school_location_id' => $this->request->data['User']['school_location_id'],
                'username'           => $this->request->data['User']['email'],
                'external_id'        => $this->request->data['User']['external_id']
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

    public function load($type)
    {
        $this->isAuthorizedAs(['Administrator', 'Account manager', 'School manager', 'School management']);

        $params = $this->request->data;

        $filters = array();
        parse_str($params['filters'], $filters);
        $filters = $filters['data']['User'];

        unset($params['filters']);

        $params['filter'] = [];

        if (!empty($filters['name'])) {
            $params['filter']['name'] = $filters['name'];
        }

        if (!empty($filters['name_first'])) {
            $params['filter']['name_first'] = $filters['name_first'];
        }

        if (isset($filters['external_id']) && !empty($filters['external_id'])) {
            $params['filter']['external_id'] = $filters['external_id'];
        }

        if (isset($filters['school_location_id']) && !empty($filters['school_location_id'])) {
            $params['filter']['school_location_id'] = $filters['school_location_id'];
        }

        if (!empty($filters['username'])) {
            $params['filter']['username'] = $filters['username'];
        }

        switch ($type) {
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
        if (in_array($roles['0']['name'], ['Administrator']) && $type =='teachers'){
            $this->render('load_teachers_for_school_location_switch', 'ajax');
        }
        else {
            $this->render('load_' . $type, 'ajax');
        }
    }

    public function profile_picture_upload()
    {
        $this->autoRender = false;

        $data = $this->request->data;

        if (!in_array($data['User']['file']['type'], ['image/jpg', 'image/jpeg', 'image/png'])) {
            echo '<script>window.parent.Notify.notify("Foutief bestandsformaat", "error", 3000); window.parent.Loading.hide();</script>';
            die;
        }

        if (isset($data['User']['file']['name']) && !empty($data['User']['file']['name'])) {
            $file = new File($data['User']['file']['tmp_name']);
            $tmpFile = TMP . time();
            $file->copy($tmpFile);

            $this->Session->write('user_profile_picture', $tmpFile);
        }

        echo '<script>window.parent.Loading.hide();</script>';
    }

    public function tell_a_teacher()
    {
        $this->isAuthorizedAs(['Teacher']);

        if ($this->request->is('post') || $this->request->is('put')) {
            // from
            // User => [
            //  'username' => ['a','b','c'],
            //  'name_suffix' => ['','van',''],
            //    ...
            // to
            // data => [
            //  [
            //      'username' => 'a',
            //      'name_suffix' => '',
            //  ],
            //  [
            //      'username' => 'b',
            //      'name_suffix' => 'van',
            //  ],
            //  [
            //      'username' => 'c',
            //      'name_suffix' => '',
            //  ]
            //];
            $data = [
                'data' => [],
                'user_roles' => [1],
                'send_welcome_mail' => true,
                'invited_by' => AuthComponent::user('id'),
            ];

            foreach($this->request->data['User'] as $key => $ar){
                foreach($ar as $i => $value) {
                    $data['data'][$i][$key] = $value;
                }
            };

            if (!isset($data['school_location_id'])) {
                $data['school_location_id'] = AuthComponent::user()['school_location_id'];
            }

            $result = $this->UsersService->addUserWithTellATeacher('teacher', $data);

            if ($result === false){
                 $this->formResponse(
                     false,
                     [
                         'error' => implode(',',$this->UsersService->getErrors())
                     ]
                 );
            }
            else {
                if ($result === true) {
                    $this->formResponse(
                        true
                    );
                } elseif ($result == 'external_code') {
                    $this->formResponse(
                        false,
                        [
                            'error' => 'external_code'
                        ]
                    );
                } elseif ($result == 'username') {
                    $this->formResponse(
                        false,
                        [
                            'error' => 'username'
                        ]
                    );
                } else {
                    $this->formResponse(
                        false,
                        ['error' => $result]
                    );
                }
            }

            die;
        }


        $this->render('tell_a_teacher', 'ajax');
    }

    public function add($type, $parameter1 = null, $parameter2 = null)
    {
        $this->isAuthorizedAs(['Administrator', 'Account manager', 'School manager', 'School management']);

        if ($this->request->is('post') || $this->request->is('put')) {
            $data = $this->request->data['User'];

            if ($type == 'teachers') {
                $data['user_roles'] = [1];

                if (!isset($data['school_location_id'])) {
                    $data['school_location_id'] = AuthComponent::user()['school_location_id'];
                }
            }

            if ($type == 'students') {
                $data['user_roles'] = [3];
            }

            if ($type == 'parents') {
                $data['user_roles'] = [9];
                $data['student_parents_of'] = [$this->Session->read('owner')];
            }

            if ($type == 'management') {
                $data['user_roles'] = [7];

                if ($this->Session->check('class_id')) {
                    $data['manager_school_classes'] = [$this->Session->read('class_id')];
                }

                if (!isset($data['school_location_id'])) {
                    $data['school_location_id'] = AuthComponent::user()['school_location_id'];
                }
            }

            if ($type == 'managers') {
                if (!isset($data['school_location_id']) && !isset($data['school_id'])) {
                    $data['school_location_id'] = AuthComponent::user()['school_location_id'];
                }

                $data['user_roles'] = [6];
            }

            if ($type == 'mentors') {
                if ($this->Session->check('class_id')) {
                    $data['mentor_school_classes'] = [$this->Session->read('class_id')];
                }
                $data['user_roles'] = [8];
            }


            $result = $this->UsersService->addUser($type, $data);

            if ($this->Session->check('user_profile_picture')) {
                $this->UsersService->updateProfilePicture($result['id'], $this->Session->read('user_profile_picture'));
                $this->Session->delete('user_profile_picture');
            }

            if (isset($result['id'])) {
                $this->formResponse(
                    true,
                    [
                        'id' => $result['id']
                    ]
                );
            } elseif ($result == 'external_code') {
                $this->formResponse(
                    false,
                    [
                        'error' => 'external_code'
                    ]
                );
            } elseif ($result == 'username') {
                $this->formResponse(
                    false,
                    [
                        'error' => 'username'
                    ]
                );
            } else {
                $this->formResponse(
                    false,
                    ['error' => $result]
                );
            }

            die;
        }

        if ($type == 'teachers') {
            $this->set('school_locations', $this->SchoolLocationsService->getSchoolLocationList());
        }

        if ($type == 'managers' || $type == 'mentors') {
            $this->set('school_locations', $this->SchoolLocationsService->getSchoolLocationList());
            $this->set('schools', $this->SchoolsService->getSchoolList());
        }

        if ($type == 'students') {
            $this->set('school_classes', $this->SchoolClassesService->getClassesList());
            $this->set('school_locations', $this->SchoolLocationsService->getSchoolLocationList());
            $this->set('class_id', $parameter1);
        }

        if ($type == 'accountmanagers') {
            $this->set('sales_organisations', $this->UsersService->getSalesOrganisations());
        }

        if ($type == 'parents') {
            $this->Session->write('owner', $parameter1);
        }

        $this->Session->delete('user_profile_picture');

        $this->set('parameter1', $parameter1);
        $this->set('parameter2', $parameter2);

        $this->render('add_' . $type, 'ajax');
    }

    public function delete($user_id)
    {
        $this->isAuthorizedAs(['Administrator', 'Account manager', 'School manager', 'School management']);

        if($this->request->is('delete')) {
            $this->autoRender = false;

            $this->formResponse(
                $this->UsersService->deleteUser($user_id) ? true : false,
                []
            );
        }
    }

    public function menu()
    {
        $roles = AuthComponent::user('roles');

        $menus = array();

        foreach ($roles as $role) {
            if ($role['name'] == 'Administrator') {
                $menus['accountmanagers'] = "Accountmanagers";
                $menus['lists'] = "Database";
            }

            if ($role['name'] == 'Account manager') {
                $menus['lists'] = "Database";
                $menus['files'] = "Bestanden";
            }

            if ($role['name'] == 'School manager') {
                $menus['users'] = "Gebruikers";
                $menus['lists'] = "Database";
                $menus['analyses'] = "Analyses";
            }

            if ($role['name'] == 'Teacher') {
                $menus['library'] = "Itembank";
                $menus['tests'] = "Toetsing";
                $menus['analyses'] = "Analyses";
                $menus['other'] = "Overig";
            }

            if ($role['name'] == 'Student') {
                $menus['tests'] = "Toetsing";
                $menus['analyses'] = "Analyse";
                $menus['messages'] = "Berichten";
            }

            if ($role['name'] == 'School management') {
                $menus['analyses'] = "Analyse";
                $menus['messages'] = "Berichten";
            }
            $menus['knowledgebase'] = "Kennisbank";

            if ($role['name'] == 'Teacher') {
                $menus['tell_a_teacher'] = "<i class='fa fa-bullhorn' style='color:#FF3333;font-weight:bold;'></i> Nodig een collega uit!";
            }
        }

        $this->set('menus', $menus);
    }

    public function tiles()
    {
        $roles = AuthComponent::user('roles');

        $tiles = array();

        $tiles['kennisbank'] = [
            'menu'  => 'knowledgebase',
            'icon'  => 'testlist',
            'title' => 'Bezoek de kennisbank',
            'path'  => 'https://support.test-correct.nl',
            'type'  => 'externalpopup',
        ];

        foreach ($roles as $role) {
            if ($role['name'] == 'Administrator') {
                $tiles['users_accountmanagers'] = array(
                    'menu'  => 'accountmanagers',
                    'icon'  => 'testlist',
                    'title' => 'Gebruikers',
                    'path'  => '/users/index/accountmanagers'
                );

                $tiles['umbrella_organisations'] = array(
                    'menu'  => 'lists',
                    'icon'  => 'testlist',
                    'title' => 'Koepelorganisaties',
                    'path'  => '/umbrella_organisations'
                );

                $tiles['schools'] = array(
                    'menu'  => 'lists',
                    'icon'  => 'testlist',
                    'title' => 'Scholengemeenschap',
                    'path'  => '/schools'
                );

                $tiles['school_locations'] = array(
                    'menu'  => 'lists',
                    'icon'  => 'testlist',
                    'title' => 'Schoollocaties',
                    'path'  => '/school_locations'
                );
                $tiles['teachers'] = array(
                    'menu' => 'lists',
                    'icon' => 'testlist',
                    'title' => 'Docenten',
                    'path' => '/users/index/teachers'
                );
                $tiles['students'] = array(
                    'menu'  => 'lists',
                    'icon'  => 'testlist',
                    'title' => 'Studenten',
                    'path'  => '/users/index/students'
                );

                $tiles['teacherstats'] = array(
                    'menu'  => 'lists',
                    'icon'  => 'testlist',
                    'title' => 'Docent statistieken',
                    'path'  => '/admin/teacher_stats'
                );

                $tiles['qtiimport'] = array(
                    'menu'  => 'lists',
                    'icon'  => 'testlist',
                    'title' => 'Qti Import',
                    'path'  => '/qtiimport/index'
                );
            }

            if ($role['name'] == 'Account manager') {
                $tiles['users_administrators'] = array(
                    'menu'  => 'users',
                    'icon'  => 'testlist',
                    'title' => 'Schoolbeheerder',
                    'path'  => '/users/index/managers'
                );

                $tiles['umbrella_organisations'] = array(
                    'menu'  => 'lists',
                    'icon'  => 'testlist',
                    'title' => 'Koepelorganisaties',
                    'path'  => '/umbrella_organisations'
                );

                $tiles['qtiimport_cito'] = array(
                    'menu'  => 'lists',
                    'icon'  => 'testlist',
                    'title' => 'QTI Cito',
                    'path'  => '/qtiimport_cito'
                );

                $tiles['qtiimport_batch_cito'] = array(
                    'menu'  => 'lists',
                    'icon'  => 'testlist',
                    'title' => 'QTI Batch Cito',
                    'path'  => '/qtiimport_batch_cito'
                );

                $tiles['attainments_import'] = array(
                    'menu'  => 'lists',
                    'icon'  => 'testlist',
                    'title' => 'Attainments Import',
                    'path'  => '/attainments'
                );


                $tiles['schools'] = array(
                    'menu'  => 'lists',
                    'icon'  => 'testlist',
                    'title' => 'Scholengemeenschap',
                    'path'  => '/schools'
                );

                $tiles['school_locations'] = array(
                    'menu'  => 'lists',
                    'icon'  => 'testlist',
                    'title' => 'Schoollocaties',
                    'path'  => '/school_locations'
                );

                $tiles['class_uploads'] = array(
                    'menu'  => 'files',
                    'icon'  => 'testlist',
                    'title' => 'Klassen bestanden',
                    'path'  => '/file_management/classuploads'
                );

                $tiles['test_uploads'] = array(
                    'menu'  => 'files',
                    'icon'  => 'testlist',
                    'title' => 'Toetsbestanden',
                    'path'  => '/file_management/testuploads'
                );

                $tiles['onboarding_wizard_report'] = array(
                    'menu' => 'files',
                    'icon' => 'testlist',
                    'title' => 'Demo tour rapport',
                    'type' => 'download',
                    'path' => '/users/onboarding_wizard_report'
                );
            }

            if ($role['name'] == 'School manager') {
                $tiles['users_teachers'] = array(
                    'menu'  => 'users',
                    'icon'  => 'testlist',
                    'title' => 'Docenten',
                    'path'  => '/users/index/teachers'
                );

                $tiles['users_management'] = array(
                    'menu'  => 'users',
                    'icon'  => 'testlist',
                    'title' => 'Directieleden',
                    'path'  => '/users/index/management'
                );

                $tiles['school_years'] = array(
                    'menu'  => 'lists',
                    'icon'  => 'testlist',
                    'title' => 'Schooljaren',
                    'path'  => '/school_years'
                );

                $tiles['sections'] = array(
                    'menu'  => 'lists',
                    'icon'  => 'testlist',
                    'title' => 'Secties',
                    'path'  => '/sections'
                );

                $tiles['school_class'] = array(
                    'menu'  => 'lists',
                    'icon'  => 'testlist',
                    'title' => 'Klassen',
                    'path'  => '/school_classes'
                );

                $tiles['students'] = array(
                    'menu'  => 'users',
                    'icon'  => 'testlist',
                    'title' => 'Studenten',
                    'path'  => '/users/index/students'
                );

                $tiles['classes_analyses'] = array(
                    'menu'  => 'analyses',
                    'icon'  => 'analyse-klassen',
                    'title' => 'Klassen',
                    'path'  => '/analyses/school_classes_overview'
                );

                $tiles['student_analyses'] = array(
                    'menu'  => 'analyses',
                    'icon'  => 'analyse-leerlingen',
                    'title' => 'Studenten',
                    'path'  => '/analyses/students_overview'
                );
            }

            if ($role['name'] == 'Teacher') {
                $tiles['tests_overview'] = array(
                    'menu'  => 'library',
                    'icon'  => 'testlist',
                    'title' => 'Toetsen',
                    'path'  => '/tests/index'
                );

                if(AuthComponent::user('hasCitoToetsen')) {
                    $tiles['tests_cito_overview'] = array(
                        'menu' => 'library',
                        'icon' => 'testlist',
                        'title' => 'CITO Toetsen op maat',
                        'path' => '/cito_tests/index'
                    );
                }

                $tiles['questions_overview'] = array(
                    'menu'  => 'library',
                    'icon'  => 'questionlist',
                    'title' => 'Vragen',
                    'path'  => '/questions/index'
                );

                $tiles['tests_planned'] = array(
                    'menu'  => 'tests',
                    'icon'  => 'gepland',
                    'title' => 'Geplande toetsen',
                    'path'  => '/test_takes/planned_teacher'
                );

                $tiles['tests_surveillance'] = array(
                    'menu'  => 'tests',
                    'icon'  => 'surveilleren',
                    'title' => 'Surveilleren',
                    'path'  => '/test_takes/surveillance'
                );

                $tiles['tests_taken'] = array(
                    'menu'  => 'tests',
                    'icon'  => 'afgenomen',
                    'title' => 'Afgenomen',
                    'path'  => '/test_takes/taken_teacher'
                );

//                $tiles['tests_discussed'] = array(
//                    'menu' => 'tests',
//                    'icon' => 'bespreken',
//                    'title' => 'Bespreken',
//                    'path' => '/test_takes/discussion'
//                );

                $tiles['tests_examine'] = array(
                    'menu'  => 'tests',
                    'icon'  => 'nakijken',
                    'title' => 'Nakijken',
                    'path'  => '/test_takes/to_rate'
                );

                $tiles['tests_graded'] = array(
                    'menu'  => 'tests',
                    'icon'  => 'becijferd',
                    'title' => 'Becijferd',
                    'path'  => '/test_takes/rated'
                );

                $tiles['analyse'] = array(
                    'menu'  => 'analyses',
                    'icon'  => 'analyse-leraar',
                    'title' => 'Uw analyse',
                    'path'  => '/analyses/teacher/' . AuthComponent::user('id')
                );

                $tiles['analyse_student'] = array(
                    'menu'  => 'analyses',
                    'icon'  => 'analyse-leerlingen',
                    'title' => 'Studenten',
                    'path'  => '/analyses/students_overview'
                );

                $tiles['analyse_classes'] = array(
                    'menu'  => 'analyses',
                    'icon'  => 'analyse-klassen',
                    'title' => 'Klassen',
                    'path'  => '/analyses/school_classes_overview'
                );

                $tiles['messages'] = array(
                    'menu'  => 'other',
                    'icon'  => 'messages',
                    'title' => 'Berichten',
                    'path'  => '/messages'
                );

                $tiles['teacher_classes'] = [
                    'menu'  => 'other',
                    'icon'  => 'testlist',
                    'title' => 'Mijn klassen',
                    'path'  => '/teacher_classes'
                ];
                $tiles['teacher_test_uploads'] = [
                    'menu'  => 'other',
                    'icon'  => 'testlist',
                    'title' => 'Aangeboden toetsen',
                    'path'  => '/file_management/testuploads'
                ];

                $tiles['tell_a_teacher'] = array(
                    'menu' => 'tell_a_teacher',
                    'icon' => 'testlist',
                    'title' => 'Stuur een uitnodiging',
                    'path' => '/users/tell_a_teacher',
                    'type' => 'popup',
                    'width'=> 650
                );
            }

            if ($role['name'] == 'Student') {
                $tiles['tests_planned'] = array(
                    'menu'  => 'tests',
                    'icon'  => 'gepland',
                    'title' => 'Geplande toetsen',
                    'path'  => '/test_takes/planned_student'
                );
                $tiles['tests_discussed'] = array(
                    'menu'  => 'tests',
                    'icon'  => 'bespreken',
                    'title' => 'Te bespreken',
                    'path'  => '/test_takes/taken_student'
                );

                $tiles['tests_glance'] = array(
                    'menu'  => 'tests',
                    'icon'  => 'inzien',
                    'title' => 'Inzien',
                    'path'  => '/test_takes/discussed_glance'
                );

                $tiles['tests_rated'] = array(
                    'menu'  => 'tests',
                    'icon'  => 'becijferd',
                    'title' => 'Becijferd',
                    'path'  => '/test_takes/rated_student'
                );

                $tiles['analyses_student'] = array(
                    'menu'  => 'analyses',
                    'icon'  => 'analyse-leerling',
                    'title' => 'Jouw analyse',
                    'path'  => '/analyses/student/' . AuthComponent::user('id')
                );

                $tiles['messages'] = array(
                    'menu'  => 'messages',
                    'icon'  => 'messages',
                    'title' => 'Berichten',
                    'path'  => '/messages'
                );
            }

            if ($role['name'] == 'School management') {
                $tiles['classes_analyses'] = array(
                    'menu'  => 'analyses',
                    'icon'  => 'analyse-klassen',
                    'title' => 'Klassen',
                    'path'  => '/analyses/school_classes_overview'
                );

                $tiles['teachers'] = array(
                    'menu'  => 'analyses',
                    'icon'  => 'analyse-leraar',
                    'title' => 'Docenten',
                    'path'  => '/analyses/teachers_overview'
                );
                $tiles['students'] = array(
                    'menu'  => 'analyses',
                    'icon'  => 'analyse-leerlingen',
                    'title' => 'Studenten',
                    'path'  => '/analyses/students_overview'
                );

                $tiles['messages'] = array(
                    'menu'  => 'messages',
                    'icon'  => 'messages',
                    'title' => 'Berichten',
                    'path'  => '/messages'
                );
            }
        }

        $this->set('tiles', $tiles);
    }

    public function password_reset()
    {
        if ($this->request->is('post')) {
            $this->autoRender = false;

            $data = $this->request->data['User'];

            if ($data['password'] != $data['password_new']) {
                $this->formResponse(false, [
                    'message' => 'Wachtwoorden komen niet overeen'
                ]);

                die;
            }

            $user_id = AuthComponent::user('id');

            $result = $this->UsersService->resetPasswordForm($user_id, $data);


            if ($result != '{"old_password":["Record does not match stored value"]}') {
                $this->formResponse(true);
            } else {
                $this->formResponse(false, [
                    'message' => 'Wachtwoorden komen niet overeen'
                ]);
            }
        }
    }

    public function info()
    {
        $this->autoRender = false;
        $info = AuthComponent::user();

        $student = false;
        $teacher = false;

        foreach ($info['roles'] as $role) {
            if ($role['name'] == 'Student') {
                $student = true;
            } elseif ($role['name'] == 'Teacher') {
                $teacher = true;
            }
        }

        if (empty($info['name_suffix'])) {
            $info['name_suffix'] = "";
        }

        if (!$student) {
            $info['name_first'] = substr($info['name_first'], 0, 1) . '.';
        }

        $info['isStudent'] = $student;
        $info['isTeacher'] = $teacher;

        $return = [];
        $allowed = ['name_first', 'name_suffix', 'name', 'abbriviation', 'isTeacher', 'isStudent'];
        foreach ($allowed as $key) {
            $return[$key] = array_key_exists($key, $info) ? $info[$key] : '';
        }

        echo json_encode($return);
    }

    public function import($type)
    {

    }

    public function doImport($type)
    {
        $data['data'] = $this->request->data;

        $result = $this->UsersService->doImport($data);
        if (!$result) {
            $this->formResponse(false, $this->UsersService->getErrors());
            return false;
        }
        $this->formResponse(true, []);
    }

    public function doImportTeachers()
    {
        $data['data'] = $this->request->data;

        $result = $this->UsersService->doImportTeacher($data);

        if (!$result) {
            $this->formResponse(false, $this->UsersService->getErrors());
            return false;
        }
        $this->formResponse(true, []);
    }

}
