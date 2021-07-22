<?php

App::uses('AppController', 'Controller');
App::uses('UsersService', 'Lib/Services');
App::uses('SchoolClassesService', 'Lib/Services');
App::uses('SchoolLocationsService', 'Lib/Services');
App::uses('SchoolYearsService', 'Lib/Services');
App::uses('SchoolsService', 'Lib/Services');
App::uses('UmbrellaOrganisationsService', 'Lib/Services');
App::uses('HelperFunctions', 'Lib');
App::uses('Securimage', 'webroot/img');
App::uses('DeploymentService', 'Lib/Services');
App::uses('WhitelistIpService', 'Lib/Services');
App::uses('TestsService', 'Lib/Services');

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
        $this->Auth->allowedActions = array('login', 'status', 'get_config', 'forgot_password', 'reset_password', 'register_new_teacher', 'register_new_teacher_successful', 'registereduix', 'temporary_login');

        $this->UsersService = new UsersService();
        $this->SchoolClassesService = new SchoolClassesService();
        $this->SchoolLocationsService = new SchoolLocationsService();
        $this->SchoolYearsService = new SchoolYearsService();
        $this->SchoolsService = new SchoolsService();
        $this->UmbrellaOrganisationsService = new UmbrellaOrganisationsService();
        $this->DeploymentService = new DeploymentService();
        $this->WhitelistIpService = new WhitelistIpService();
        $this->TestsService = new TestsService();

        parent::beforeFilter();
    }

    public function registereduix()
    {
        if ($this->request->is('post')) {
            $result = $this->UsersService->addUserEduIx(
                $this->params['url']['ean'], $this->params['url']['edurouteSessieID'],
                $this->params['url']['signature'], $this->request->data['User']
            );
            $response = json_decode($result);
            if (property_exists($response, 'errors') && count((array) $response->errors) > 0) {
                $this->formResponse(false, ['message' => $response->message]);
            } else {
                $this->formResponse(true, ['data' => $response]);
            }
            exit();
        }

        $response = $this->UsersService->registerEduIx(
            $this->params['url']['ean'], $this->params['url']['edurouteSessieID'], $this->params['url']['signature']
        );

        $user = new stdClass;
        $user->school_location = $response['eduProfile']['homeOrganization'];
        $user->name_first = $response['eduProfile']['givenName'];
        $user->name_suffix = $response['ediProfile']['personTussenvoegsels'];
        $user->name = $response['eduProfile']['sn'];
        $user->username = $response['eduProfile']['personRealID'];

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

        $captchaSet = false;
        if ($this->request->is('post') || $this->request->is('put')) {
            $appType = $this->request->data['appType'];

            if (isset($this->request->data['User']['captcha_string']) && !empty($this->request->data['User']['captcha_string'])) {
                $captchaSet = true;

                if (!class_exists('Securimage')) {
                    include_once(WWW_ROOT.'img/securimage.php');
                }
                $this->SecureImage = new Securimage();
                if ($this->SecureImage->check($this->request->data['User']['captcha_string']) == false) {
                    // error captcha not ok
                    $this->formResponse(false, [
                        'message'     => 'De ingevoerde beveiligingscode wat niet correct, probeer het nogmaals',
                        'showCaptcha' => true
                    ]);
                    return false;
                }
            }

            if ($this->Auth->login()) {
                App::uses('BugsnagLogger', 'Lib');
                BugsnagLogger::getInstance()->configureUser(AuthComponent::user());
                //              $this->formResponse(true, array('data' => AuthComponent::user(), 'message' => $message));
                if ($this->Session->check('TLCHeader')) {// && $this->Session->read('TLCHeader') !== 'not secure...') {
                    if ($this->UsersService->hasRole('student')) {

                        $versionCheckResult = $this->Session->check('TLCVersionCheckResult') ? $this->Session->read('TLCVersionCheckResult') : 'NOTALLOWED';
                        $data = [
                            'os'                   => $this->Session->check('TLCOs') ? $this->Session->read('TLCOs') : 'not set in session',
                            'version'              => $this->Session->check('TLCVersion') ? $this->Session->read('TLCVersion') : 'not set in session',
                            'version_check_result' => $versionCheckResult,
                            'headers'              => $this->Session->check('headers') ? json_encode($this->Session->read('headers')) : 'not set is session',
                        ];

                        $this->UsersService->storeAppVersionInfo($data, AuthComponent::user('id'));

                        if ($versionCheckResult === 'NOTALLOWED') {
                            // somebody should be logedout, but we don't do this yet
//                            $this->Auth->logout();
//                            $this->Session->destroy();
//                            $message = 'Uw versie van de app wordt niet meer ondersteund. Download de nieuwe versie via http://www.test-correct.nl';
//                            $this->formResponse(false, ['message' => $message]);
//                            exit();
                        }
                    }

                    // check if teacher has active contract
                    if ($this->UsersService->hasRole('teacher')) {

                        $user_school_location = AuthComponent::user('school_location');

                        if (null === $user_school_location || !isset($user_school_location['activated']) || $user_school_location['activated'] == 0) {

                            $this->logout();

                            $this->formResponse(false,
                                ['message' => 'U kunt niet inloggen omdat het contract van uw school niet actief is. Neem contact met ons op als u denkt dat dit een vergissing is.']);

                            return false;
                        }
                    }
                }


                // no need to expose user info
                $this->formResponse(true, ['message' => $message]);
            } else {
                // Check if there's a captcha reason to fail or that the data is just not okay
                if (!empty($this->request->data['User']['email']) && !empty($this->request->data['User']['password'])) {
                    if ($this->UsersService->doWeNeedCaptcha($this->request->data['User']['email'])) {
                        if ($captchaSet === true) {
                            // username/ password was incorrect
                            $this->formResponse(false, ['showCaptcha' => true]);
                            return false;
                        }
                        $this->formResponse(false, ['showCaptcha' => true, 'message' => 'Voer de beveiligingscode in']);
                        return false;
                    }
                }
                $this->formResponse(false);
                return false;
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
                $this->request->data['User'], $userId
            );
            $result = (json_decode($response));

            if (property_exists($result, 'errors') && count((array) $result->errors) > 0) {
                $this->formResponse(false, $result);
            } else {
                $this->formResponse(true, ['data' => $response]);
            }
            exit();
        }
        $data = $this->UsersService->getRegisteredNewTeacherByUserId($userId);
        $this->set('user', (object) $data);
    }

    public function register_new_teacher()
    {

        $onboarding_url_config_variable = 'shortcode.shortcode.redirect';
        $onboarding_url = $this->get_config($onboarding_url_config_variable);
        $location_string = 'location:'.$onboarding_url;

        header($location_string);

        exit();

        if ($this->request->is('post')) {
            $response = $this->UsersService->registerNewTeacher(
                $this->request->data['User']
            );
            $result = (json_decode($response));

            if (property_exists($result, 'errors') && count((array) $result->errors) > 0) {
                $this->formResponse(false, $result);
            } else {
                $this->formResponse(true, ['data' => $response]);
            }
            exit();
        }

    }

    public function get_config($laravel_config_variable)
    {

        $response = $this->UsersService->getConfig($laravel_config_variable);

        return $response['status'];

    }

    public function register_new_teacher_successful()
    {

    }

    protected function getSessionHeaderData()
    {
        $ar = ['TLCHeader', 'TLCOs', 'TLCVersion', 'TLCVersionCheckResult', 'headers'];
        $allAvailable = true;
        $returnAr = [];
        foreach ($ar as $item) {
            if ($this->Session->check($item)) {
                $returnAr[$item] = $this->Session->read($item);
            } else {
                $allAvailable = false;
            }
        }

        if ($allAvailable === false) {
            return null;
        }
        return $returnAr;
    }

    protected function reinitFromSessionHeaderData($data)
    {
        foreach ($data as $key => $val) {
            $this->Session->write($key, $val);
        }
    }

    public function logout()
    {
        $this->autoRender = false;
        $this->Auth->logout();
        $tlcSessionHeaderData = $this->getSessionHeaderData();
        $this->Session->destroy();
        if ($tlcSessionHeaderData !== null && is_array($tlcSessionHeaderData)) {
            $this->Session->renew();
            $this->reinitFromSessionHeaderData($tlcSessionHeaderData);
        }
        App::uses('BugsnagLogger', 'Lib');
        BugsnagLogger::getInstance()->unsetUser();
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
                $result ? true : false, ['progress' => $result['progress']]
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

    public function marketing_report()
    {

        $this->isAuthorizedAs(['Account manager']);

        $result = $this->UsersService->createMarketingReport($this->request->data);

        if (!$result) {
            exit;
        }

        $this->response->body($result);
        $this->response->header('Content-Disposition', 'attachment; filename=marketing_report_'.date('YmdHi').'.xls');

        return $this->response;

    }

    public function school_location_report()
    {

        $this->isAuthorizedAs(['Account manager']);

        $result = $this->UsersService->createSchoolLocationReport($this->request->data);

        if (!$result) {
            exit;
        }

        $this->response->body($result);
        $this->response->header('Content-Disposition',
            'attachment; filename=school_location_report_'.date('YmdHi').'.xls');

        return $this->response;

    }

    public function welcome()
    {
        $roles = AuthComponent::user('roles');

        $menus = array();

        $view = "welcome";
        $hasSchoolManagerRole = false;
        $should_display_import_incomplete_panel = false;
        foreach ($roles as $role) {
            if ($role['name'] == 'Teacher') {


                $view = "welcome_teacher";
                $wizardSteps = $this->UsersService->getOnboardingWizard(AuthComponent::user('uuid'));

                $verified = CakeSession::read('Auth.User.account_verified');
                if ($verified == null) {
                    $verified = $this->UsersService->getAccountVerifiedValue();
                    CakeSession::write('Auth.User.account_verified', $verified);
                }
                $this->set('wizard_steps', $wizardSteps);
                $this->set('account_verified', $verified);
                $this->set('progress',
                    floor($wizardSteps['count_sub_steps_done'] / $wizardSteps['count_sub_steps'] * 100));

                App::uses('MaintenanceHelper', 'Lib');

                $should_display_import_incomplete_panel = $this->UsersService->shouldDisplayImportIncompletePanel();
                $this->set('maintenanceNotification', MaintenanceHelper::getInstance()->getMaintenanceNotification());
            }

            if ($role['name'] == 'Student') {
                $view = "welcome_student";
//                if($this->Session->check('AppTooOld') && $this->Session->read('AppTooOld') === true){
//                    if($this->check('AppOS') && $this->read('AppOS') === 'windows') {
//                        $view = "welcome_student_update";
//                    }
//                }
            }
            if (strtolower($role['name']) === 'tech administrator') {
                $view = "welcome_tech_administrator";
                $this->set('deployments', $this->DeploymentService->index());
                $this->set('deploymentStatuses', $this->DeploymentService->getStatuses());
                $this->set('whitelistIps', $this->WhitelistIpService->index());
            }

            if (strtolower($role['name']) === 'school manager') {
                $hasSchoolManagerRole = true;
                $should_display_import_incomplete_panel = $this->UsersService->shouldDisplayImportIncompletePanelAccountManager();
            }
        }
        $this->set('hasSchoolManagerRole', $hasSchoolManagerRole);
        $this->set('should_display_import_incomplete_panel', $should_display_import_incomplete_panel);

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
                $school_locations = [0 => 'Alle'];
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
            $result = file_get_contents(APP.WEBROOT_DIR.'/img/ico/user.png');
            $this->response->type('image/png');
            $this->response->body($result);
            return $this->response;
        }

        $user = $this->UsersService->getUser($user_id);

        if (empty($user['profile_image_size'])) {
            $result = file_get_contents(APP.WEBROOT_DIR.'/img/ico/user.png');
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
                    $result ? true : false, []
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
            if (array_key_exists('teacher_external_id', $this->request->data['User'])) {
                $this->request->data['User']['external_id'] = $this->request->data['User']['teacher_external_id'];
            }
            $data = $this->request->data['User'];
            $result = $this->UsersService->updateUser($user_id, $data);

            if ($this->Session->check('user_profile_picture')) {
                $result = $this->UsersService->updateProfilePicture($user_id,
                    $this->Session->read('user_profile_picture'));


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
                    if (isset($error['errors']['username'])) {
                        if (stristr($error['errors']['username'][0], 'failed on dns')) {
                            $response = "Het domein van het opgegeven e-mailadres is niet geconfigureerd voor e-mailadressen";
                        } else {
                            $response = "Dit e-mailadres is al in gebruik";
                        }

                    }
                    if (isset($error['errors']['external_id'])) {
                        $response = "Studentennummer is al in gebruik";
                    }
                } catch (\Throwable $th) {

                }

                $this->formResponse(
                    false, [$response]
                );

                die;
            }

            $this->formResponse(
                true, []
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

            case 1: //Teacher
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

                $this->set('school_classes',
                    HelperFunctions::getInstance()->revertSpecialChars($this->SchoolClassesService->getClassesList()));
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

    public function move_school_location($userId)
    {

        $this->isAuthorizedAs(['Administrator']);
        $user = $this->UsersService->getUser($userId);

        if ($this->request->is('post') || $this->request->is('put')) {
            if ($user->school_location_id == $this->request->data['User']['school_location_id']) {
                // we don't need to do anything, same school location, so done already
                $this->formResponse(
                    true, []
                );
                die;
            }

            $params = [
                'school_location_id' => $this->request->data['User']['school_location_id'],
            ];

            $result = $this->UsersService->move_school_location($userId, $params);

            if ($result === false) {

                $this->formResponse(
                    false, $this->UsersService->getErrors()
                );
            } else {
                $this->formResponse(
                    true, []
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
                $result ? true : false, []
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
        if (in_array($roles['0']['name'], ['Administrator']) && $type == 'teachers') {
            $this->render('load_teachers_for_school_location_switch', 'ajax');
        } else {
            $this->render('load_'.$type, 'ajax');
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
            $tmpFile = TMP.time();
            $file->copy($tmpFile);

            $this->Session->write('user_profile_picture', $tmpFile);
        }

        echo '<script>window.parent.Loading.hide();</script>';
    }

    public function tell_a_teacher()
    {
        $this->isAuthorizedAs(['Teacher']);

        $shortcode = $this->UsersService->getShortcode(AuthComponent::user('id'));

        if ($this->request->is('post') || $this->request->is('put')) {

            $data = [
                'data'       => [
                    'email_addresses' => $this->request->data['emailAddresses'],
                    'message'         => $this->request->data['message']
                ],
                'user_roles' => [1],
                'invited_by' => AuthComponent::user('id'),
                'step'       => $this->request->data['step'],
                'shortcode'  => $shortcode['data']['code'],
            ];

            if (!isset($data['school_location_id'])) {
                $data['school_location_id'] = AuthComponent::user()['school_location_id'];
            }

            $result = $this->UsersService->addUserWithTellATeacher('teacher', $data);
        }

        if ($this->request->data['message']) {
            $message = $this->request->data['message'];
        } else {
            $message = 'Ik wil je graag uitnodigen voor het platform Test-Correct. Ik gebruik het al en kan het zeker aanraden. Met Test-Correct kun je digitaal Toetsen en goed samenwerken. Maak jouw gratis account aan en ga aan de slag!';
        }
        $this->set('message', $message);
        $errors = json_decode($result)->errors;
        $this->set('errors', $errors->form[0]);
        $this->set('email_addresses', $this->request->data['emailAddresses']);
        $this->set('shortcode', $shortcode['data']['code']);
        $this->set('url', $shortcode['url']);
        $dataMessage = 'data.message';

        if ($this->request->data['step'] == 2 && $errors === null) {
            $this->render('tell_a_teacher_complete', 'ajax');
            return;
        }
        if ($this->request->data['step'] == 2 && property_exists($errors, 'data.message')) {
            $this->set('errorMessage', $errors->$dataMessage[0]);
            $this->render('tell_a_teacher_step_2', 'ajax');
            return;
        }
        if ($this->request->data['step'] == 1 && $this->request->data['stepback']) {
            $this->set('stepback', true);
            $this->render('tell_a_teacher', 'ajax');
            return;
        }
        if ($result === true && !empty($this->request->data['emailAddresses'])) {
            $this->render('tell_a_teacher_step_2', 'ajax');
        } else {
            $this->render('tell_a_teacher', 'ajax');
        }
    }

    public
    function add(
        $type,
        $parameter1 = null,
        $parameter2 = null
    ) {
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
                $this->UsersService->updateProfilePicture(getUUID($result, 'get'),
                    $this->Session->read('user_profile_picture'));
                $this->Session->delete('user_profile_picture');
            }

            if (isset($result['id'])) {
                $this->formResponse(
                    true,
                    [
                        'id'   => $result['id'],
                        'uuid' => getUUID($result, 'get'),
                    ]
                );
            } elseif ($result == 'external_code') {
                $this->formResponse(
                    false, [
                        'error' => 'external_code'
                    ]
                );
            } elseif ($result == 'username') {
                $this->formResponse(
                    false, [
                        'error' => 'username'
                    ]
                );
            } elseif ($result == 'dns') {
                $this->formResponse(
                    false, [
                        'error' => 'dns'
                    ]
                );
            } elseif ($result == 'external_id') {
                $this->formResponse(
                    false, [
                        'error' => 'external_id'
                    ]
                );
            } else {
                $this->formResponse(
                    false, ['error' => $result]
                );
            }

            die;
        }

        if ($type == 'teachers') {
            $this->set('school_locations', $this->SchoolLocationsService->getSchoolLocationList());
        }

        if ($type == 'managers' || $type == 'mentors') {
            $this->set('school_locations', $this->SchoolLocationsService->getSchoolLocationList());
            $this->set('current_school', $this->SchoolLocationsService->getSchoolLocation($parameter2)['id']);
            $this->set('schools', $this->SchoolsService->getSchoolList());
        }

        if ($type == 'students') {
            $this->set('school_classes',
                HelperFunctions::getInstance()->revertSpecialChars($this->SchoolClassesService->getClassesList()));
            $this->set('school_locations', $this->SchoolLocationsService->getSchoolLocationList());
            $this->set('class_id', $this->SchoolClassesService->getClass($parameter1)['id']);
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

        $this->render('add_'.$type, 'ajax');
    }

    public
    function delete(
        $user_id
    ) {
        $this->isAuthorizedAs(['Administrator', 'Account manager', 'School manager', 'School management']);

        if ($this->request->is('delete')) {
            $this->autoRender = false;

            $this->formResponse(
                $this->UsersService->deleteUser($user_id) ? true : false, []
            );
        }
    }

    public
    function menu()
    {
        $roles = AuthComponent::user('roles');

        $menus = array();

        foreach ($roles as $role) {
            if (strtolower($role['name']) === 'tech administrator') {
                $menus['index'] = "";
            }
            if ($role['name'] == 'Administrator') {
                $menus['accountmanagers'] = "Accountmanagers";
                $menus['lists'] = "Database";
            }

            if ($role['name'] == 'Account manager') {
                $menus['lists'] = "Database";
                $menus['files'] = "Bestanden";
                $menus['qti'] = "QTI";
            }

            if ($role['name'] == 'School manager') {
                $menus['users'] = "Gebruikers";
                $menus['lists'] = "Database";
                $menus['analyses'] = "Analyses";
            }

            if ($role['name'] == 'Teacher') {
                //Dashboard and Results is prepended to the menu in menu.js
//                $menus['dashboard'] = "Dashboard";
                $menus['library'] = "Toetsen";
                $menus['tests'] = "Ingepland";
                $menus['taken'] = "Afgenomen";
//                $menus['results'] = "Resultaten";
                $menus['analyses'] = "Analyses";
                $menus['classes'] = "Klassen";
//                $menus['other'] = "Overig";
//                $menus['support'] = "Support";
            }

            if ($role['name'] == 'Student') {
                $menus['tests'] = "Toetsing";
                $menus['analyses'] = "Analyse";
//                $menus['messages'] = "Berichten";
//                $menus['support'] = "Support";
            }

            if ($role['name'] == 'School management') {
                $menus['analyses'] = "Analyse";
//                $menus['messages'] = "Berichten";
            }


//            if ($role['name'] == 'Teacher') {
//                $menus['tell_a_teacher'] = "
//                                                <button class='button cta-button button-sm' style='cursor: pointer;'
//                                                     onClick=\"Popup.load('/users/tell_a_teacher', 800);\">
//                                                    <span style='margin-right: 10px'>Nodig een collega uit!</span>
//                                                    <svg width='17' height='16' xmlns='http://www.w3.org/2000/svg'>
//                                                        <g fill='none' fill-rule='evenodd' stroke-linecap='round' stroke='white' stroke-width='2'>
//                                                            <path stroke-linejoin='round' d='M1 1l14 7-14 7 2-7z'/>
//                                                            <path d='M3 8h10M1 1l14 7-14 7'/>
//                                                        </g>
//                                                    </svg>
//                                                </button>
//                                            ";
//            }
        }

        $this->set('menus', $menus);
    }

    public
    function tiles()
    {
        $roles = AuthComponent::user('roles');

        $tiles = array();


        $tiles['kennisbank'] = [
            'menu'  => 'support',
            'icon'  => 'knowledgebase',
            'title' => 'Kennisbank',
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
                    'menu'  => 'lists',
                    'icon'  => 'testlist',
                    'title' => 'Docenten',
                    'path'  => '/users/index/teachers'
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

                $tiles['rttiimport'] = array(
                    'menu'  => 'lists',
                    'icon'  => 'testlist',
                    'title' => 'RTTI Import',
                    'path'  => '/rttiimport/index'
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

                $tiles['qtiimport'] = array(
                    'menu'  => 'qti',
                    'icon'  => 'testlist',
                    'title' => 'Qti Import',
                    'path'  => '/qtiimport/index'
                );

                $tiles['qtiimport_cito'] = array(
                    'menu'  => 'qti',
                    'icon'  => 'testlist',
                    'title' => 'Cito',
                    'path'  => '/qtiimport_cito'
                );

                $tiles['qtiimport_batch_cito'] = array(
                    'menu'  => 'qti',
                    'icon'  => 'testlist',
                    'title' => 'Batch Cito',
                    'path'  => '/qtiimport_batch_cito'
                );

                $tiles['attainments_import'] = array(
                    'menu'  => 'lists',
                    'icon'  => 'testlist',
                    'title' => 'Attainments Import',
                    'path'  => '/attainments'
                );

                $tiles['attainmentscito_import'] = array(
                    'menu'  => 'lists',
                    'icon'  => 'testlist',
                    'title' => 'Attainments CITO koppeling',
                    'path'  => '/attainments_cito'
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

                $tiles['marketing_report'] = array(
                    'menu'  => 'files',
                    'icon'  => 'testlist',
                    'title' => 'Marketing Rapport',
                    'type'  => 'download',
                    'path'  => '/users/marketing_report'
                );

                $tiles['school_location_report'] = array(
                    'menu'  => 'files',
                    'icon'  => 'testlist',
                    'title' => 'School locatie Rapport',
                    'type'  => 'download',
                    'path'  => '/users/school_location_report'
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
                $tiles['create_content'] = array(
                    'menu'  => 'library',
                    'icon'  => 'testlist',
                    'title' => 'Toets creëren',
                    'type'  => 'popup',
                    'path'  => '/tests/create_content'
                );

                $tiles['tests_overview'] = array(
                    'menu'  => 'library',
                    'icon'  => 'testlist',
                    'title' => 'Toetsenbank',
                    'path'  => '/tests/index'
                );

//                if (AuthComponent::user('hasSharedSections')) {
//                    $tiles['tests_shared_sections_overview'] = array(
//                        'menu'  => 'library',
//                        'icon'  => 'testlist',
//                        'title' => 'Toetsen scholengemeenschap',
//                        'path'  => '/shared_sections_tests/index'
//                    );
//                }

                $tiles['questions_overview'] = array(
                    'menu'  => 'library',
                    'icon'  => 'questionlist',
                    'title' => 'Vragenbank',
                    'path'  => '/questions/index'
                );

//                if (AuthComponent::user('hasCitoToetsen')) {
//                    $tiles['tests_cito_overview'] = array(
//                        'menu'  => 'library',
//                        'icon'  => 'testlist',
//                        'title' => 'CITO Toetsen op maat',
//                        'path'  => '/cito_tests/index'
//                    );
//                }

                $tiles['tests_planned'] = array(
                    'menu'  => 'tests',
                    'icon'  => 'gepland',
                    'title' => 'Mijn ingeplande toetsen',
                    'path'  => '/test_takes/planned_teacher'
                );

                $tiles['tests_surveillance'] = array(
                    'menu'  => 'tests',
                    'icon'  => 'surveilleren',
                    'title' => 'Surveilleren',
                    'path'  => '/test_takes/surveillance'
                );

                $tiles['tests_taken'] = array(
                    'menu'  => 'taken',
                    'icon'  => 'afgenomen',
                    'title' => 'Mijn afgenomen toetsen',
                    'path'  => '/test_takes/taken_teacher'
                );

//                $tiles['tests_discussed'] = array(
//                    'menu' => 'taken',
//                    'icon' => 'bespreken',
//                    'title' => 'Bespreken',
//                    'path' => '/test_takes/discussion'
//                );

                $tiles['tests_examine'] = array(
                    'menu'  => 'taken',
                    'icon'  => 'nakijken',
                    'title' => 'Nakijken & normeren',
                    'path'  => '/test_takes/to_rate'
                );

//                $tiles['tests_graded'] = array(
//                    'menu'  => 'results',
//                    'icon'  => 'becijferd',
//                    'title' => 'Beoordeeld',
//                    'path'  => '/test_takes/rated'
//                );

                $tiles['analyse'] = array(
                    'menu'  => 'analyses',
                    'icon'  => 'analyse-leraar',
                    'title' => 'Mijn analyses',
                    'path'  => '/analyses/teacher/'.AuthComponent::user('uuid')
                );

                $tiles['analyse_student'] = array(
                    'menu'  => 'analyses',
                    'icon'  => 'analyse-leerlingen',
                    'title' => 'Mijn studenten',
                    'path'  => '/analyses/students_overview'
                );

                $tiles['analyse_classes'] = array(
                    'menu'  => 'analyses',
                    'icon'  => 'analyse-klassen',
                    'title' => 'Mijn klassen',
                    'path'  => '/analyses/school_classes_overview'
                );

//                $tiles['messages'] = array(
//                    'menu'  => 'other',
//                    'icon'  => 'messages',
//                    'title' => 'Berichten',
//                    'path'  => '/messages'
//                );

                $tiles['teacher_classes'] = [
                    'menu'  => 'classes',
                    'icon'  => 'testlist',
                    'title' => 'Mijn klassen',
                    'path'  => '/teacher_classes'
                ];

                $tiles['school_location_classes'] = [
                    'menu'  => 'classes',
                    'icon'  => 'testlist',
                    'title' => 'Mijn schoollocatie',
                    'path'  => '/teacher_classes/school_location_classes'
                ];

                $tiles['teacher_test_uploads'] = [
                    'menu'  => 'library',
                    'icon'  => 'testlist',
                    'title' => 'Mijn uploads',
                    'path'  => '/file_management/testuploads'
                ];

//                $tiles['webinar'] = [
//                    'menu'  => 'support',
//                    'icon'  => 'webinar',
//                    'title' => 'Webinar',
//                    'type'  => 'externalpopup',
//                    'path'  => 'https://embed.webinargeek.com/ac16aaa56a08d79ca2535196591dd91b20b70807849b5879fe',
//                ];
//
//                $tiles['supportmail'] = [
//                    'menu'  => 'support',
//                    'icon'  => 'send-email',
//                    'title' => 'E-mail',
//                    'type'  => 'externallink',
//                    'path'  => 'mailto:support@test-correct.nl',
//                ];
//
//                $tiles['supportchat'] = [
//                    'menu'  => 'support',
//                    'icon'  => 'send-email',
//                    'title' => 'Chat',
//                    'path'  => '',
//                ];
//
//                $tiles['updates'] = [
//                    'menu'  => 'support',
//                    'icon'  => 'send-email',
//                    'title' => 'Updates & onderhoud',
//                    'type'  => 'externalpopup',
//                    'width'=> '1000',
//                    'path'  => 'https://support.test-correct.nl/knowledge/wat-zijn-de-laatste-updates',
//                ];

                /*
                $tiles['tell_a_teacher'] = array(
                    'menu'  => 'tell_a_teacher',
                    'icon'  => 'testlist',
                    'title' => 'Stuur een uitnodiging',
                    'path'  => '/users/tell_a_teacher',
                    'type'  => 'popup',
                    'width' => 800
                );
                 *
                 */
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
                    'path'  => '/analyses/student/'.AuthComponent::user('uuid')
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

    public
    function password_reset()
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

            $user_id = AuthComponent::user('uuid');

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

    public
    function info()
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
            $info['name_first'] = substr($info['name_first'], 0, 1).'.';

            $info['school_location_list'] = array_map(function ($location) use ($info) {
                return (object) [
                    'uuid'   => $location['uuid'],
                    'name'   => $location['name'],
                    'active' => $location['active'],
                ];
            }, $this->UsersService->getSchoolLocationList());
        }

        $info['isStudent'] = $student;
        $info['isTeacher'] = $teacher;

        $return = [];
        $allowed = [
            'name_first',
            'name_suffix',
            'name',
            'abbriviation',
            'isTeacher',
            'isStudent',
            'school_location_list',
            'school_location_id'
        ];

        foreach ($allowed as $key) {
            $return[$key] = array_key_exists($key, $info) ? $info[$key] : '';
        }

        echo json_encode($return);
    }

    public
    function import(
        $type
    ) {
        if ($type == 'students') {
            $this->isAuthorizedAs(['Administrator', 'Account manager', 'School manager', 'School management']);
            $school_location = AuthComponent::user('school_location');
            $this->set('school_location_id', $school_location['id']);
            $this->set('school_location', $school_location);
            $this->render('import_students');
            return;
        }
        if ($type == 'teachers_bare') {
            $this->isAuthorizedAs(['Administrator', 'Account manager', 'School manager', 'School management']);
            $school_location = AuthComponent::user('school_location');
            $this->set('school_location_id', $school_location['id']);
            $this->set('school_location', $school_location);
            $this->render('import_teachers_bare');
            return;
        }
    }

    public function doImportStudentsWithClasses()
    {
        $this->isAuthorizedAs(['Administrator', 'Account manager', 'School manager', 'School management']);
        $school_location = AuthComponent::user('school_location');
        $data['data'] = $this->request->data;
        $result = $this->SchoolClassesService->doImportStudentsWithClasses($school_location['uuid'], $data);
        if (!$result) {
            $response = $this->translateError($this->SchoolClassesService);
            $this->formResponse(false, $response);
            return false;
        }
        $this->formResponse(true, []);
    }

    public
    function doImport(
        $type
    ) {
        $data['data'] = $this->request->data;

        $result = $this->UsersService->doImport($data);
        if (!$result) {
            $this->formResponse(false, $this->UsersService->getErrors());
            return false;
        }
        $this->formResponse(true, []);
    }

    public
    function doImportTeachers()
    {
        $data['data'] = $this->request->data;

        $result = $this->UsersService->doImportTeacher($data);

        if (!$result) {
            $this->formResponse(false, $this->UsersService->getErrors());
            return false;
        }
        $this->formResponse(true, []);
    }

    public
    function doImportTeachersBare()
    {
        $data['data'] = $this->request->data;

        $result = $this->UsersService->doImportTeacherBare($data);

        if (!$result) {
            $this->formResponse(false, $this->UsersService->getErrors());
            return false;
        }
        $this->formResponse(true, []);
    }

    public
    function setActiveSchoolLocation(
        $uuid
    ) {
//        if (! $this->isAuthorizedAs(['Teacher']) ) {
//            $this->formResponse(false, '');
//            return;
//        }

        $result = $this->UsersService->switchSchool($uuid);

        if (!$result) {
            $this->formResponse(false, $this->UsersService->getErrors());
            return false;
        }
        $this->formResponse(true, $result);

    }

    public
    function add_existing_teachers()
    {
//        if (!$this->isAuthorizedAs('Administrator') ) {
//            $this->formResponse(false, 'U heeft onvoldoende rechten om dit te mogen uitvoeren');
//            return false;
//        }

////        $result = $this->UsersService->addExistingTeacher($this->request->user);
//
//        if (!$result) {
//            $this->formResponse(false, $this->UsersService->getErrors());
//            return false;
//        }
//        $this->formResponse(true, $result);

    }

    public
    function load_existing_teachers()
    {
        $params = $this->request->data;

        $filters = array();
        parse_str($params['filters'], $filters);
        $filters = $filters['data']['ExistingTeacher'];

        unset($params['filters']);

        $params['filter'] = [];

        if (!empty($filters['name'])) {
            $params['filter']['name'] = $filters['name'];
        }

        if (!empty($filters['name_first'])) {
            $params['filter']['name_first'] = $filters['name_first'];
        }


        if (!empty($filters['username'])) {
            $params['filter']['username'] = $filters['username'];
        }

        //if ($this->isAuthorizedAs(['Administrator']) ){
        $result = $this->UsersService->getTeachersFromOtherLocations($params);
        $this->set('users', $result);
//        }
    }

    public
    function add_existing_teacher_to_schoolLocation()
    {
        $this->isAuthorizedAs(['School manager']);

        if ($this->request->is('post')) {
            $result = $this->UsersService->addExistingTeacherToSchoolLocation($this->request->data['user']);
            if (!$result) {
                $this->formResponse(false, $this->UsersService->getErrors());
                return false;
            }
            $this->formResponse(true, $result);
        }
    }

    public
    function delete_existing_teacher_from_schoolLocation()
    {
        $this->isAuthorizedAs(['School manager']);

        if ($this->request->is('delete')) {
            $result = $this->UsersService->removeExistingTeacherToSchoolLocation($this->request->data['user']);
            if (!$result) {
                $this->formResponse(false, $this->UsersService->getErrors());
                return false;
            }
            $this->formResponse(true, $result);
        }
    }


    public
    function resendEmailVerificationMail()
    {
        $this->isAuthorizedAs(["Teacher"]);

        if ($this->request->is('post')) {
            $result = $this->UsersService->resendEmailVerificationMail();
            if (!$result) {
                $this->formResponse(false, $this->UsersService->getErrors());
                return false;
            }
            $this->formResponse(true, []);
        }
        die();
    }

    public
    function temporary_login(
        $tlid
    ) {
        $result = $this->UsersService->getUserWithTlid($tlid);
        $this->Auth->login($result);

        try {
            $this->render('templogin', 'templogin');
        } catch (Exception $e) {
            echo '
                <html>
                    <head>
                        <meta http-equiv="refresh" content="0;url=/" />
                        <title>Een moment</title>
                    </head>
                    <body>
                        Een moment...
                    </body>
                </html>
              ';
            exit();
        }
    }

    public function toggle_verified($uuid)
    {
        $this->isAuthorizedAs(['Administrator', 'Account manager', 'School manager', 'School management']);
        $response = $this->UsersService->toggleAccountVerified($uuid);
        echo json_encode($response);
        exit;
    }

    private function translateError($service)
    {
        if (!array_key_exists('error', $service->getErrors())) {
            return $service->getErrors();
        }
        if (stristr($service->getErrors()['error'], 'School class id not found for class')) {
            return (str_replace('School class id not found for class', 'SchoolKlas',
                    $service->getErrors()['error']).' niet gevonden!');
        }

        return $service->getErrors()['error'];
    }

    public function teacher_complete_user_import_main_school_class()
    {
        $this->isAuthorizedAs('Teacher');

        if ($this->request->is('put')) {
            $response = $this->SchoolClassesService->updateWithEductionLevelsForMainClasses($this->request->data);
            if ($response ==  false) {

                echo json_encode(['error' => $this->SchoolClassesService->getErrors()]);
                exit;
            }
            $response['data']['done'] = !$this->UsersService->shouldDisplayImportIncompletePanel();
            echo json_encode(['result' => $response['data']]);
            exit;
        }

        $classesList = $this->SchoolClassesService->getClasses([
            'filter' => [
                'current' => 1,
                'is_main_school_class' => 1,
            ],
            'mode' => 'import_data',
        ]);


        $eductionLevels = $this->SchoolLocationsService->getSchoolLocationEducationLevels(
            getUUID(AuthComponent::user('school_location'), 'get'),
            true
        );
        $this->set('classes_list', $classesList);
        $this->set('education_levels', $eductionLevels);
    }

    public function teacher_complete_user_import_education_level_cluster_class()
    {
        $this->isAuthorizedAs('Teacher');

        if ($this->request->is('put')) {
            $response = $this->SchoolClassesService->updateWithEductionLevelsForClusterClasses($this->request->data);
            if ($response ==  false) {

                echo json_encode(['error' => $this->SchoolClassesService->getErrors()]);
                exit;
            }
            $response['data']['done'] = !$this->UsersService->shouldDisplayImportIncompletePanel();
            echo json_encode(['result' => $response['data']]);
            exit;
        }

        $classesList = $this->SchoolClassesService->getClasses([
            'filter' => [
                'current'              => 1,
                'is_main_school_class' => 0,
                'demo' => 0,
            ],
            'mode'   => 'import_data',
        ]);

        $eductionLevels = $this->SchoolLocationsService->getSchoolLocationEducationLevels(
            getUUID(AuthComponent::user('school_location'), 'get'),
            true
        );
        $this->set('classes_list', $classesList);
        $this->set('education_levels', $eductionLevels);
    }

    public function teacher_complete_user_import_subject_cluster_class()
    {
        $this->isAuthorizedAs('Teacher');

        if ($this->request->is('put')) {
            $response = $this->SchoolClassesService->updateTeachersWithSubjectsForClusterClasses($this->request->data);
            if ($response ==  false) {

                echo json_encode(['error' => $this->SchoolClassesService->getErrors()]);
                exit;
            }
            $response['data']['done'] = !$this->UsersService->shouldDisplayImportIncompletePanel();
            echo json_encode(['result' => $response['data']]);
            exit;
        }

        $teachersList = $this->UsersService->getTeachersList();
        $teacherEntries = [];
        $checkedByTeacher = [];
        $taughtSubjects = [];
        foreach($teachersList as $teacherRecord) {
            if (! is_array($teacherEntries[$teacherRecord['class_id']])){
                $teacherEntries[$teacherRecord['class_id']] = [];
            }
            $teacherEntries[$teacherRecord['class_id']][] = $teacherRecord['subject_id'];
            $taughtSubjects[] = $teacherRecord['subject_id'];
            if ($teacherRecord['checked_by_teacher']) {
                $checkedByTeacher[$teacherRecord['class_id']] = $teacherRecord['checked_by_teacher'];
            }
        }

        $classesList = $this->SchoolClassesService->getClasses([
            'filter' => [
                'current' => 1,
                'demo' => 0,
            ],
            'mode'   => 'import_data',
        ]);
        $subjects = $this->TestsService->getSubjects(false, 'all', true, true);

        $this->set('classes_list', $classesList);
        $this->set('subjects', $subjects);
        $this->set('taught_subjects', $taughtSubjects);
        $this->set('teacher_entries', $teacherEntries);
        $this->set('checked_by_teacher', $checkedByTeacher);
    }

    public function school_manager_complete_user_import_main_school_class()
    {
        $this->isAuthorizedAs('School manager');

        if ($this->request->is('put')) {
            $response = $this->SchoolClassesService->updateWithEductionLevelsForMainClasses($this->request->data);
            $response['data']['done'] = !$this->UsersService->shouldDisplayImportIncompletePanel();
            echo json_encode(['result' => $response['data']]);
            exit;
        }

        $classesList = $this->SchoolClassesService->getClasses([
            'filter' => [
                'current'              => 1,
                'is_main_school_class' => 1,
            ],
            'mode'   => 'import_data',
        ]);
        $eductionLevels = $this->SchoolLocationsService->getSchoolLocationEducationLevels(
            getUUID(AuthComponent::user('school_location'), 'get')
        );
        $this->set('classes_list', $classesList);
        $this->set('education_levels', $eductionLevels);
    }

    public function prevent_logout()
    {
        $this->set('opened_by_user', $this->params['url']['opened_by_user']);
    }
}
