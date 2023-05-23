<?php

use Pusher\Pusher;

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
App::uses('SupportService', 'Lib/Services');
App::uses('InfoService', 'Lib/Services');

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
        $this->Auth->allowedActions = array('login', 'status', 'get_config', 'forgot_password', 'reset_password', 'register_new_teacher', 'register_new_teacher_successful', 'registereduix', 'temporary_login', 'handleTemporaryLoginOptions', 'get_laravel_login_page', 'logout_from_laravel');

        $this->UsersService = new UsersService();
        $this->SchoolClassesService = new SchoolClassesService();
        $this->SchoolLocationsService = new SchoolLocationsService();
        $this->SchoolYearsService = new SchoolYearsService();
        $this->SchoolsService = new SchoolsService();
        $this->UmbrellaOrganisationsService = new UmbrellaOrganisationsService();
        $this->DeploymentService = new DeploymentService();
        $this->WhitelistIpService = new WhitelistIpService();
        $this->TestsService = new TestsService();
        $this->SupportService = new SupportService();
        $this->InfoService = new InfoService();

        parent::beforeFilter();
    }

    public function student_splash()
    {

    }

    public function registereduix()
    {
        if ($this->request->is('post')) {
            $result = $this->UsersService->addUserEduIx(
                $this->params['url']['ean'], $this->params['url']['edurouteSessieID'],
                $this->params['url']['signature'], $this->request->data['User']
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
        //Only on test domains;
        $this->set('useLaravelLogin', true);

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
                    include_once(WWW_ROOT . 'img/securimage.php');
                }
                $this->SecureImage = new Securimage();
                if ($this->SecureImage->check($this->request->data['User']['captcha_string']) == false) {
                    // error captcha not ok
                    $this->formResponse(false, [
                        'message'     => __("De ingevoerde beveiligingscode wat niet correct, probeer het nogmaals"),
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
                            'user_os'              => $this->Session->check('UserOsPlatform') ? json_encode($this->Session->read('UserOsPlatform')) : 'not set is session',
                            'user_os_version'      => $this->Session->check('UserOsVersion') ? json_encode($this->Session->read('UserOsVersion')) : 'not set is session',
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
                                ['message' => __("U kunt niet inloggen omdat het contract van uw school niet actief is. Neem contact met ons op als u denkt dat dit een vergissing is.")]);

                            return false;
                        }
                    }
                }
                $this->setUserLanguage();
                // no need to expose user info
                $this->formResponse(true, ['message' => $message]);
            } else {
                if ($this->Auth->_authenticateObjects[0]->error === "NEEDS_LOGIN_ENTREE") {
                    $this->formResponse(false, ['message' => __("Je gegevens zijn nog niet compleet. Log de eerste keer in via Entree")]);
                    return false;
                }


                // Check if there's a captcha reason to fail or that the data is just not okay
                if (!empty($this->request->data['User']['email']) && !empty($this->request->data['User']['password'])) {
                    if ($this->UsersService->doWeNeedCaptcha($this->request->data['User']['email'])) {
                        if ($captchaSet === true) {
                            // username/ password was incorrect
                            $this->formResponse(false, ['showCaptcha' => true]);
                            return false;
                        }
                        $this->formResponse(false, ['showCaptcha' => true, 'message' => __("Voer de beveiligingscode in")]);
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

        $onboarding_url_config_variable = 'shortcode.shortcode.redirect';
        $onboarding_url = $this->get_config($onboarding_url_config_variable);
        $location_string = 'location:' . $onboarding_url;

        header($location_string);

        exit();

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

    public function logout_from_laravel()
    {
        $this->logout();
        $this->autoRender = false;
        $url = $this->get_laravel_login_page();
        if (substr_count(Router::url($this->here, true), 'testportal2.test-correct')) {
            $url = str_replace('welcome.test', 'welcome2.test', $url);
        }
        header('Location: ' . $url);
        exit;
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
        $this->response->header('Content-Disposition', 'attachment; filename=marketing_report_' . date('YmdHi') . '.xls');

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
            'attachment; filename=school_location_report_' . date('YmdHi') . '.xls');

        return $this->response;

    }

    public function welcome()
    {
        $roles = AuthComponent::user('roles');

        $this->InfoService = new InfoService();

        $menus = array();

        $view = "welcome";
        $hasSchoolManagerRole = false;
        $should_display_import_incomplete_panel = false;
        $this->set('infos', $this->InfoService->dashboard());
        if($temporaryLoginOptions = json_decode(CakeSession::read('temporaryLoginOptions'),true)){
            $finalRedirectTo = null;
            if(array_key_exists('finalRedirectTo',$temporaryLoginOptions)){
                $finalRedirectTo = $temporaryLoginOptions['finalRedirectTo'];
                unset($temporaryLoginOptions['finalRedirectTo']);
                if($finalRedirectTo) {
                    CakeSession::write('temporaryLoginOptions', json_encode($temporaryLoginOptions));
                }
            }
            $this->set('finalRedirectTo',$finalRedirectTo);
        }

        foreach ($roles as $role) {
            if ($role['name'] == 'Teacher') {
                $afterLoginMessage = '';
                if ($temporaryLoginOptions = json_decode(CakeSession::read('temporaryLoginOptions'), true)) {
                    if (array_key_exists('afterLoginMessage', $temporaryLoginOptions)) {
                        $afterLoginMessage = $temporaryLoginOptions['afterLoginMessage'];
                        unset($temporaryLoginOptions['afterLoginMessage']);
                        if ($afterLoginMessage) {
                            CakeSession::write('temporaryLoginOptions', json_encode($temporaryLoginOptions));
                        }
                    }
                }
                $this->set('afterLoginMessage', $afterLoginMessage);
                $view = "welcome_teacher";
                $wizardSteps = $this->UsersService->getOnboardingWizard(AuthComponent::user('uuid'));

                $verified = CakeSession::read('Auth.User.account_verified');
                if ($verified == null) {
                    $verified = $this->UsersService->getAccountVerifiedValue();
                    CakeSession::write('Auth.User.account_verified', $verified);
                }

                $this->set('has_features',!! count($this->InfoService->features()));

                $this->set('wizard_steps', $wizardSteps);
                $this->set('account_verified', $verified);
                $this->set('language', $this->Session->read('Config.language'));
                $this->set('progress',
                    floor($wizardSteps['count_sub_steps_done'] / $wizardSteps['count_sub_steps'] * 100));

                App::uses('MaintenanceHelper', 'Lib');

                $should_display_import_incomplete_panel = $this->UsersService->shouldDisplayImportIncompletePanel();
                $this->set('maintenanceNotification', MaintenanceHelper::getInstance()->getMaintenanceNotification());

                $timedInfo = $this->UsersService->getTimeSensitiveUserRecords(AuthComponent::user('uuid'));
                $userGeneralTermsLog = $timedInfo['userGeneralTermsLog'];
                $trialPeriod = $timedInfo['trialPeriod'];
                $this->handleGeneralTermsForUser($userGeneralTermsLog);
                $this->handleTrialPeriodForUser($trialPeriod);
                $this->handleIfNewFeatureRelease();
            }

            if ($role['name'] == 'Student') {
                $headers = AppVersionDetector::getAllHeaders();
                $isInBrowser = AppVersionDetector::isInBrowser($headers);
                $this->set('isInBrowser', $isInBrowser);
                $needsUpdateDeadline = AppVersionDetector::needsUpdateDeadline($headers);
                $this->set('needsUpdateDeadline', $needsUpdateDeadline);

                if (AuthComponent::user('school_location.allow_new_student_environment')) {
                    $this->set('redirectToLaravel', true);
                }

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
            if (strtolower($role['name']) === 'test team') {
                $view = "welcome_test_team";
            }
        }
        $this->set('hasSchoolManagerRole', $hasSchoolManagerRole);
        if ($should_display_import_incomplete_panel) {
            $lvs_type = $this->SchoolLocationsService->getLvsType(
                getUUID(AuthComponent::user('school_location'), 'get')
            )[0];
            $this->set('lvs_type', $lvs_type);
        }
        $this->set('should_display_import_incomplete_panel', $should_display_import_incomplete_panel);

        $this->render($view, 'ajax');
    }

    public function index($type)
    {
        $this->isAuthorizedAs(['Administrator', 'Account manager', 'School manager', 'School management', 'Support']);

        switch ($type) {
            case 'accountmanagers':
                $params = [
                    'title'     => __("Accountmanagers"),
                    'add_title' => __("Nieuwe Accountmanager")
                ];
                $this->set('sales_organisations', $this->UsersService->getSalesOrganisations());
                break;

            case 'managers':
                $params = [
                    'title'     => __("Schoolbeheerder"),
                    'add_title' => __("Nieuwe Schoolbeheerder")
                ];
                break;

            case 'students':

                $params = [
                    'title'     => __("Studenten"),
                    'add_title' => __("Nieuwe student")
                ];

                $school_locations[0] = __('Alle');
                $school_locations += $this->SchoolLocationsService->getSchoolLocationList();

                $this->set('school_location', $school_locations);
                break;

            case 'teachers':
                $params = [
                    'title'     => __("Docenten"),
                    'add_title' => __("Nieuwe Docent")
                ];
                $school_locations = [0 => __('Alle')];
                $school_locations += $this->SchoolLocationsService->getSchoolLocationList();

                $this->set('school_location', $school_locations);
                break;

            case 'management':
                $params = [
                    'title'     => __("Directieleden"),
                    'add_title' => __("Nieuw Directielid")
                ];
                break;

            case 'support':
                $params = [
                    'title'     => __('Supportmedewerkers'),
                    'add_title' => __('Nieuwe medewerker')
                ];
                break;
            case 'test_team':
                $params = [
                    'title'     => __('Test team'),
                    'add_title' => __('Nieuwe tester')
                ];
                break;
            case 'trial_students':
                $params = [
                    'title' => __('Studenten'),
                ];
                break;
            case 'trial_teachers':
                $params = [
                    'title' => __('Docenten'),
                ];
                $school_locations = [0 => __('Alle')];
                $school_locations += $this->SchoolLocationsService->getSchoolLocationList();
                $this->set('school_location', $school_locations);
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
                $this->formResponse(false, ['error' => __("Er dient een wachtwoord opgegven te worden")]);
            } elseif ($data['password'] !== $data['password_confirmation']) {
                $this->formResponse(false, ['error' => __("De wachtwoorden komen niet overeen")]);
            } else {
                $result = $this->UsersService->updatePasswordForUser($user_id, $data);

                if (array_key_exists('errors', $result)) {
                    return $this->formResponse(false, $result['errors']);
                }
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
        $this->isAuthorizedAs(['Administrator', 'Account manager', 'School manager', 'School management', 'Support']);

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
                $response = __("De gebruiker kon niet worden bijgewerkt");

                //try to decode the error (JSON to array)
                //or fail and show a general error
                try {
                    $error = json_decode($result, true);
                    if (isset($error['errors']['username'])) {
                        if (stristr($error['errors']['username'][0], 'failed on dns')) {
                            $response = __("Het domein van het opgegeven e-mailadres is niet geconfigureerd voor e-mailadressen");
                        } else {
                            $response = __("Dit e-mailadres is al in gebruik");
                        }

                    }
                    if (isset($error['errors']['external_id'])) {
                        $response = __("Studentennummer is al in gebruik");
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
                $this->set('school_location', $user['school_location']);
                $this->set('can_be_exam_coordinator', $this->canTeacherBeExamCoordinator($user_id));
                $this->render('edit_teachers', 'ajax');
                break;

            case 7: //Management
                $this->render('edit_management', 'ajax');
                break;

            case 9: //Parent
                $this->render('edit_parents', 'ajax');
                break;

            case 3: //Student
                $params['filter']['without_guest_classes'] = 1;
                $activeClasses = [];

                foreach ($user['User']['student_school_classes'] as $class) {
                    $activeClasses[] = $class['id'];
                }

                $this->set('active_classes', $activeClasses);

                $this->set('school_classes',
                    HelperFunctions::getInstance()->revertSpecialChars($this->SchoolClassesService->getClassesList($params)));
                $this->render('edit_students', 'ajax');
                break;

            case 11: //Support
                $this->render('edit_support', 'ajax');
                break;
            case 12: //Test team
                $this->render('edit_test_team', 'ajax');
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

            case 11: //Support employees
                $this->set('takeOverLogs', $this->SupportService->getTakeOverLogsForUser(getUUID($user, 'get')));
                $this->render('view_support', 'ajax');
                break;
            case 12: //Test team
                $this->render('view_test_team', 'ajax');
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

        $this->set('school_locations', $this->SchoolLocationsService->getSchoolLocationList(['with_trial_notation' => true]));

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
        $this->isAuthorizedAs(['Administrator', 'Account manager', 'School manager', 'School management', 'Support']);

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
                $params['with'] = ['trial_info'];
                break;

            case 'students':
                $params['filter']['role'] = 3;
                $params['filter']['without_guests'] = 1;
                $params['with'] = ['school_location', 'studentSchoolClasses'];
                break;

            case 'management':
                $params['filter']['role'] = 7;
                break;

            case 'support':
                $params['filter']['role'] = 11;
                break;

            case 'test_team':
                $params['filter']['role'] = 12;
                break;
            case 'trial_students':
                $params['filter']['role'] = 3;
                $params['filter']['trial'] = true;
                $params['filter']['without_guests'] = 1;
                break;
            case 'trial_teachers':
                $params['filter']['trial'] = true;
                $params['filter']['role'] = 1;
                $params['with'] = ['trial_info'];
                break;
        }
        $roles = AuthComponent::user('roles');
        $role = $roles['0']['name'];

        $params['order']['name'] = 'asc';
        $users = $this->UsersService->getUsers($params);
        if (strpos($type, 'trial_') !== false || $role === 'Support') {
            [$trialStatus, $trialDaysLeft] = $this->getTrialPeriodStatusses($users);
            $this->set('trialStatus', $trialStatus);
            $this->set('trialDaysLeft', $trialDaysLeft);
        }

        $this->set('role', $role);
        $this->set('users', $users);
        $this->set('type', $type);
        if (in_array($role, ['Administrator']) && $type == 'teachers') {
            $this->render('load_teachers_for_school_location_switch', 'ajax');
        } else {
            $this->render('load_' . $type, 'ajax');
        }
    }

    public function profile_picture_upload()
    {
        $this->autoRender = false;

        $data = $this->request->data;

        if (!in_array($data['User']['file']['type'], ['image/jpg', 'image/jpeg', 'image/png'])) {
            echo '<script>window.parent.Notify.notify("' . __("Foutief bestandsformaat") . '", "error", 3000); window.parent.Loading.hide();</script>';
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
            $message = __("Ik wil je graag uitnodigen voor het platform Test-Correct. Ik gebruik het al en kan het zeker aanraden. Met Test-Correct kun je digitaal Toetsen en goed samenwerken. Maak jouw gratis account aan en ga aan de slag!");
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
                $this->Session->delete('schoolLocationsList');
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

            if ($type == 'support') {
                $data['user_roles'] = [11];
            }
            if ($type == 'test_team') {
                $data['user_roles'] = [12];
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
            $this->Session->write('schoolLocationsList', $this->SchoolLocationsService->getSchoolLocationListWithUUID());
            $this->set('school_locations', $this->SchoolLocationsService->getSchoolLocationList());
        }

        if ($type == 'managers' || $type == 'mentors') {
            $this->set('school_locations', $this->SchoolLocationsService->getSchoolLocationList());
            $this->set('current_school', $this->SchoolLocationsService->getSchoolLocation($parameter2)['id']);
            $this->set('schools', $this->SchoolsService->getSchoolList());
        }

        if ($type == 'students') {
            $params['filter']['without_guest_classes'] = 1;
            $this->set('school_classes',
                HelperFunctions::getInstance()->revertSpecialChars($this->SchoolClassesService->getClassesList($params)));
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

        $this->render('add_' . $type, 'ajax');
    }

    public function delete($user_id)
    {
        $this->isAuthorizedAs(['Administrator', 'Account manager', 'School manager', 'School management']);

        if ($this->request->is('delete')) {
            $this->autoRender = false;
            $serviceCall = $this->UsersService->deleteUser($user_id);
            $this->formResponse(
                $serviceCall['success'], ($serviceCall['success'] ? [] : $serviceCall['response'])
            );
        }
    }

    public function menu()
    {
        $newEnvironment = AuthComponent::user('school_location.allow_new_student_environment') && AuthComponent::user('roles.0.name') == 'Student';
        $useLaravelTakenPage = AuthComponent::user('school_location.feature_settings.allow_new_taken_tests_page');
        $roles = AuthComponent::user('roles');
        $isExamCoordinator = !!AuthComponent::user('is_examcoordinator');
        $usesNewAnalysesPage = !!AuthComponent::user('school_location.feature_settings.allow_analyses');

        $menus = array();

        foreach ($roles as $role) {
            if (strtolower($role['name']) === 'tech administrator') {
                $menus['index'] = "";
            }
            if ($role['name'] == 'Administrator') {
                $menus['accountmanagers'] = __("Accountmanagers");
                $menus['support_list'] = __("Support");
                $menus['test_team'] = "Test Team";
                $menus['lists'] = __("Database");
                $menus['trial'] = __("TRIAL");
            }

            if ($role['name'] == 'Account manager') {
                $menus['lists'] = __("Database");
                $menus['files'] = __("Bestanden");
                $menus['qti'] = __("QTI");
                $menus['infos'] = __('info.Info Messages');
                $menus['imports'] = __('Imports');
            }

            if ($role['name'] == 'School manager') {
                $menus['users'] = __("Gebruikers");
                $menus['lists'] = __("Database");
                $menus['analyses'] = __("Analyses");
            }

            if ($role['name'] == 'Teacher') {
                //Dashboard and Results is prepended to the menu in menu.js
                $menus['dashboard'] = ['title' => "Dashboard", 'onClick' => 'Menu.dashboardButtonAction()'];
                $menus['library'] = __("Toetsen");
                $menus['tests'] = __("Ingepland");
                if (!$isExamCoordinator) {
                    if ($useLaravelTakenPage) {
                        $menus['taken'] = [
                            'title'   => __("Afgenomen"),
                            'onClick' => 'User.goToLaravel("teacher/test_takes/taken")',
                            'selid'   => 'taken-menu-btn',
                        ];
                    } else {
                        $menus['taken'] = __("Afgenomen");
                    }
                    $menus['results'] = [
                        'title' => __("Resultaten"),
                        'onClick' => 'Navigation.load("/test_takes/rated");Menu.clearActiveMenu("results")'
                    ];
                    $menus['analyses'] = __("Analyses");
                    $menus['classes'] = __("Klassen");
                } else {
                    $menus['results'] = [
                        'title' => __("Resultaten"),
                        'onClick' => 'Navigation.load("/test_takes/rated");Menu.clearActiveMenu("results")'
                    ];
                }
            }

            if ($role['name'] == 'Student') {
                if ($newEnvironment) {
                    $menus['tests'] = [
                        'title'   => __("Toetsen"),
                        'onClick' => 'User.goToLaravel("/student/test-takes?tab=planned")'
                    ];

                    $menus['analyses'] = [
                        'title'   => __("Analyses"),
                        'onClick' => $usesNewAnalysesPage
                            ? 'User.goToLaravel("/student/analyses")'
                            : 'Navigation.load("/analyses/student/' . AuthComponent::user('uuid') . '")'
                    ];
                } else {
                    $menus['tests'] = __("Toetsen");
                    $menus['analyses'] = __("Analyses");
                }
//                $menus['messages'] = "Berichten";
//                $menus['support'] = "Support";
            }

            if ($role['name'] == 'School management') {
                $menus['analyses'] = __("Analyse");
//                $menus['messages'] = "Berichten";
            }
            if ($role['name'] == 'Support') {
                $menus['lists'] = __("Gebruikers");
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

    public function tiles()
    {
        $roles = AuthComponent::user('roles');
        $useLaravelTakenPage = AuthComponent::user('school_location.feature_settings.allow_new_taken_tests_page');
        $isExamCoordinator = !!AuthComponent::user('is_examcoordinator');
        $usesNewAnalysesPage = AuthComponent::user('school_location.feature_settings.allow_analyses') == 1;
        $isToetsenbakker = !!AuthComponent::user('isToetsenbakker');

        $tiles = array();

        $tiles['kennisbank'] = [
            'menu'  => 'support',
            'icon'  => 'knowledgebase',
            'title' => __("Kennisbank"),
            'path'  => 'https://support.test-correct.nl/knowledge',
            'type'  => 'externalpopup',
        ];

        foreach ($roles as $role) {
            if ($role['name'] == 'Administrator') {
                $tiles['users_accountmanagers'] = array(
                    'menu'  => 'accountmanagers',
                    'icon'  => 'testlist',
                    'title' => __("Gebruikers"),
                    'path'  => '/users/index/accountmanagers'
                );

                $tiles['umbrella_organisations'] = array(
                    'menu'  => 'lists',
                    'icon'  => 'testlist',
                    'title' => __("Koepelorganisaties"),
                    'path'  => '/umbrella_organisations'
                );

                $tiles['schools'] = array(
                    'menu'  => 'lists',
                    'icon'  => 'testlist',
                    'title' => __("Scholengemeenschap"),
                    'path'  => 'admin/schools',
                    'type'  => 'laravelpage'
                );

                $tiles['school_locations'] = array(
                    'menu'  => 'lists',
                    'icon'  => 'testlist',
                    'title' => __("Schoollocaties"),
                    'path'  => 'admin/school-locations',
                    'type'  => 'laravelpage'
                );
                $tiles['teachers'] = array(
                    'menu'  => 'lists',
                    'icon'  => 'testlist',
                    'title' => __("Docenten"),
                    'path'  => '/users/index/teachers'
                );
                $tiles['students'] = array(
                    'menu'  => 'lists',
                    'icon'  => 'testlist',
                    'title' => __("Studenten"),
                    'path'  => '/users/index/students'
                );

                $tiles['rttiimport'] = array(
                    'menu'  => 'lists',
                    'icon'  => 'testlist',
                    'title' => __("RTTI Import"),
                    'path'  => '/rttiimport/index'
                );

                $tiles['defaultsubjectsandsectionsimport'] = [
                    'menu'  => 'lists',
                    'icon'  => 'testlist',
                    'title' => __("Default subjects and sections import"),
                    'path'  => '/default_subjects_and_sections/index'
                ];

                $tiles['schoolandschoollocationsimport'] = [
                    'menu'  => 'lists',
                    'icon'  => 'testlist',
                    'title' => __("School and school locations import"),
                    'path'  => '/school_and_school_locations/index'
                ];


                $tiles['attainments_import_export'] = array(
                    'menu'  => 'lists',
                    'icon'  => 'testlist',
                    'title' => 'Attainments',
                    'path'  => '/attainments/upload_download_provision'
                );

                $tiles['learning_goals_import_export'] = array(
                    'menu'  => 'lists',
                    'icon'  => 'testlist',
                    'title' => 'Learning Goals',
                    'path'  => '/attainments/learning_goals_upload_download_provision'
                );

                $tiles['support'] = array(
                    'menu'  => 'support_list',
                    'icon'  => 'testlist',
                    'title' => __('Medewerkers'),
                    'path'  => '/users/index/support'
                );
                $tiles['support_logs'] = array(
                    'menu'  => 'support_list',
                    'icon'  => 'testlist',
                    'title' => __('Logs'),
                    'path'  => '/support/index'
                );
                $tiles['testers'] = array(
                    'menu'  => 'test_team',
                    'icon'  => 'testlist',
                    'title' => 'Testers',
                    'path'  => '/users/index/test_team'
                );
                $tiles['trial_teachers'] = array(
                    'menu'  => 'trial',
                    'title' => __('Docenten'),
                    'path'  => '/users/index/trial_teachers'
                );
                //Disabled students because they do not have trial accounts ?
                /*$tiles['trial_students'] = array(
                    'menu'  => 'trial',
                    'title' => __('Studenten'),
                    'path'  => '/users/index/trial_students'
                );*/
            }

            if ($role['name'] == 'Account manager') {
                $tiles['uwlr'] = array(
                    'menu'  => 'imports',
                    'icon'  => 'testlist',
                    'title' => __('UWLR'),
                    'path'  => '/uwlr',
                    'type'  => 'laravelpage',
                );
                $tiles['uwlr fetcher'] = array(
                    'menu'  => 'imports',
                    'icon'  => 'testlist',
                    'title' => __('UWLR Fetcher'),
                    'path'  => '/uwlr/fetcher',
                    'type'  => 'laravelpage',
                );

                $tiles['users_administrators'] = array(
                    'menu'  => 'users',
                    'icon'  => 'testlist',
                    'title' => __("Schoolbeheerder"),
                    'path'  => '/users/index/managers'
                );

                $tiles['umbrella_organisations'] = array(
                    'menu'  => 'lists',
                    'icon'  => 'testlist',
                    'title' => __("Koepelorganisaties"),
                    'path'  => '/umbrella_organisations'
                );

                $tiles['qtiimport'] = array(
                    'menu'  => 'qti',
                    'icon'  => 'testlist',
                    'title' => __("Qti Import"),
                    'path'  => '/qtiimport/index'
                );

                $tiles['qtiimport_cito'] = array(
                    'menu'  => 'qti',
                    'icon'  => 'testlist',
                    'title' => __("Cito"),
                    'path'  => '/qtiimport_cito'
                );

                $tiles['qtiimport_batch_cito'] = array(
                    'menu'  => 'qti',
                    'icon'  => 'testlist',
                    'title' => __("Batch Cito"),
                    'path'  => '/qtiimport_batch_cito'
                );

                $tiles['attainments_import'] = array(
                    'menu'  => 'lists',
                    'icon'  => 'testlist',
                    'title' => __("Attainments Import"),
                    'path'  => '/attainments'
                );

                $tiles['attainmentscito_import'] = array(
                    'menu'  => 'lists',
                    'icon'  => 'testlist',
                    'title' => __("Attainments CITO koppeling"),
                    'path'  => '/attainments_cito'
                );



                $tiles['schools'] = array(
                    'menu'  => 'lists',
                    'icon'  => 'testlist',
                    'title' => __("Scholengemeenschap"),
                    'path'  => 'account-manager/schools',
                    'type'  => 'laravelpage'
                );

                $tiles['school_locations'] = array(
                    'menu'  => 'lists',
                    'icon'  => 'testlist',
                    'title' => __("Schoollocaties"),
                    'path'  => 'account-manager/school-locations',
                    'type'  => 'laravelpage'
                );
                $tiles['class_uploads'] = array(
                    'menu'  => 'files',
                    'icon'  => 'testlist',
                    'title' => __("Klassen bestanden"),
                    'path'  => '/file_management/classuploads'
                );

                $tiles['test_uploads'] = array(
                    'menu'  => 'files',
                    'icon'  => 'testlist',
                    'title' => __("Toetsbestanden"),
                    'path'  => 'account-manager/file-management/testuploads',
                    'type'  => 'laravelpage',
                );

                $tiles['marketing_report'] = array(
                    'menu'  => 'files',
                    'icon'  => 'testlist',
                    'title' => __("Marketing Rapport"),
                    'type'  => 'download',
                    'path'  => '/users/marketing_report'
                );

                $tiles['school_location_report'] = array(
                    'menu'  => 'files',
                    'icon'  => 'testlist',
                    'title' => __("School locatie Rapport"),
                    'type'  => 'download',
                    'path'  => '/users/school_location_report'
                );

                $tiles['info_messages'] = array(
                    'menu'  => 'infos',
                    'icon'  => 'testlist',
                    'title' => __("info.Info Messages"),
                    'path'  => '/infos/index'
                );
            }

            if ($role['name'] == 'School manager') {
                $tiles['users_teachers'] = array(
                    'menu'  => 'users',
                    'icon'  => 'testlist',
                    'title' => __("Docenten"),
                    'path'  => '/users/index/teachers'
                );

                $tiles['users_management'] = array(
                    'menu'  => 'users',
                    'icon'  => 'testlist',
                    'title' => __("Directieleden"),
                    'path'  => '/users/index/management'
                );

                $tiles['school_years'] = array(
                    'menu'  => 'lists',
                    'icon'  => 'testlist',
                    'title' => __("Schooljaren"),
                    'path'  => '/school_years'
                );

                $tiles['sections'] = array(
                    'menu'  => 'lists',
                    'icon'  => 'testlist',
                    'title' => __("Secties"),
                    'path'  => '/sections'
                );

                $tiles['school_class'] = array(
                    'menu'  => 'lists',
                    'icon'  => 'testlist',
                    'title' => __("Klassen"),
                    'path'  => '/school_classes'
                );

                $tiles['students'] = array(
                    'menu'  => 'users',
                    'icon'  => 'testlist',
                    'title' => __("Studenten"),
                    'path'  => '/users/index/students'
                );

                $tiles['classes_analyses'] = array(
                    'menu'  => 'analyses',
                    'icon'  => 'analyse-klassen',
                    'title' => __("Klassen"),
                    'path'  => '/analyses/school_classes_overview'
                );

                $tiles['student_analyses'] = array(
                    'menu'  => 'analyses',
                    'icon'  => 'analyse-leerlingen',
                    'title' => __("Studenten"),
                    'path'  => '/analyses/students_overview'
                );
            }

            if ($role['name'] == 'Teacher') {
                if(!$isExamCoordinator) {
                    $tiles['create_content'] = array(
                        'menu'  => 'library',
                        'icon'  => 'testlist',
                        'title' => __("Toets creëren"),
                        'type'  => 'popup',
                        'path'  => '/tests/create_content'
                    );
                }
                if (AuthComponent::user('school_location.allow_new_test_bank') == 1) {
                    $tiles['tests_overview'] = array(
                        'menu'  => 'library',
                        'icon'  => 'testlist',
                        'title' => __("Toetsenbank"),
                        'path'  => 'teacher/tests?openTab=personal',
                        'type'  => 'laravelpage'
                    );
                } else {
                    $tiles['tests_overview'] = array(
                        'menu'  => 'library',
                        'icon'  => 'testlist',
                        'title' => __("Toetsenbank"),
                        'path'  => '/tests/index'
                    );
                }

//                if (AuthComponent::user('hasSharedSections')) {
//                    $tiles['tests_shared_sections_overview'] = array(
//                        'menu'  => 'library',
//                        'icon'  => 'testlist',
//                        'title' => 'Toetsen scholengemeenschap',
//                        'path'  => '/shared_sections_tests/index'
//                    );
//                }
                if(!$isExamCoordinator) {
                    $tiles['questions_overview'] = array(
                        'menu'  => 'library',
                        'icon'  => 'questionlist',
                        'title' => __("Vragenbank"),
                        'path'  => '/questions/index'
                    );
                }

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
                    'title' => __("Mijn ingeplande toetsen"),
                    'path'  => '/test_takes/planned_teacher'
                );
                if (!$isExamCoordinator) {
                    $tiles['tests_surveillance'] = array(
                        'menu'  => 'tests',
                        'icon'  => 'surveilleren',
                        'title' => __("Surveilleren"),
                        'path'  => '/test_takes/surveillance'
                    );
                    $tiles['tests_assesments'] = array(
                        'menu'  => 'tests',
                        'icon'  => 'surveilleren',
                        'title' => __("Lopende opdrachten"),
                        'path'  => '/test_takes/assignment_open_teacher'
                    );

                    if(!$useLaravelTakenPage ) {
                        $tiles['tests_taken'] = array(
                            'menu'  => 'taken',
                            'icon'  => 'afgenomen',
                            'title' => __("Mijn afgenomen toetsen"),
                            'path'  => '/test_takes/taken_teacher'
                        );

                        $tiles['tests_examine'] = array(
                            'menu'  => 'taken',
                            'icon'  => 'nakijken',
                            'title' => __("Nakijken & normeren"),
                            'path'  => '/test_takes/to_rate'
                        );
                    }

                    if ($usesNewAnalysesPage) {
                        $tiles['analyse_classes'] = array(
                            'menu'  => 'analyses',
                            'icon'  => 'analyse-klassen',
                            'title' => __("Mijn klassen"),
                            'path'  => '/teacher_analyses'
                        );
                    } else {
                        $tiles['analyse_student'] = array(
                            'menu'  => 'analyses',
                            'icon'  => 'analyse-leerlingen',
                            'title' => __("Mijn studenten"),
                            'path'  => '/analyses/students_overview'
                        );

                        $tiles['analyse_classes'] = array(
                            'menu'  => 'analyses',
                            'icon'  => 'analyse-klassen',
                            'title' => __("Mijn klassen"),
                            'path'  => '/analyses/school_classes_overview'
                        );
                    }

                    $tiles['teacher_classes'] = [
                        'menu'  => 'classes',
                        'icon'  => 'testlist',
                        'title' => __("Mijn klassen"),
                        'path'  => '/teacher_classes'
                    ];

                    $tiles['school_location_classes'] = [
                        'menu'  => 'classes',
                        'icon'  => 'testlist',
                        'title' => __("Mijn schoollocatie"),
                        'path'  => '/teacher_classes/school_location_classes'
                    ];

                    if ($isToetsenbakker) {
                        $tiles['teacher_test_uploads'] = array(
                            'menu'  => 'library',
                            'icon'  => 'testlist',
                            'title' => __("Mijn uploads"),
                            'path'  => 'teacher/file-management/testuploads',
                            'type'  => 'laravelpage'
                        );
                    } else {
                        $tiles['teacher_test_uploads'] = [
                            'menu'  => 'library',
                            'icon'  => 'testlist',
                            'title' => __("Mijn uploads"),
                            'path'  => '/file_management/testuploads'
                        ];
                    }
                }
            }

            if ($role['name'] == 'Student') {
                $tiles['tests_planned'] = array(
                    'menu'  => 'tests',
                    'icon'  => 'gepland',
                    'title' => __("Geplande toetsen"),
                    'path'  => '/test_takes/planned_student'
                );
                $tiles['tests_discussed'] = array(
                    'menu'  => 'tests',
                    'icon'  => 'bespreken',
                    'title' => __("Te bespreken"),
                    'path'  => '/test_takes/taken_student'
                );

                $tiles['tests_glance'] = array(
                    'menu'  => 'tests',
                    'icon'  => 'inzien',
                    'title' => __("Inzien"),
                    'path'  => '/test_takes/discussed_glance'
                );

                $tiles['tests_rated'] = array(
                    'menu'  => 'tests',
                    'icon'  => 'becijferd',
                    'title' => __("Becijferd"),
                    'path'  => '/test_takes/rated_student'
                );

                $tiles['analyses_student'] = array(
                    'menu'  => 'analyses',
                    'icon'  => 'analyse-leerling',
                    'title' => __("Jouw analyse"),
                    'path'  => '/analyses/student/' . AuthComponent::user('uuid')
                );

                $tiles['messages'] = array(
                    'menu'  => 'messages',
                    'icon'  => 'messages',
                    'title' => __("Berichten"),
                    'path'  => '/messages'
                );
            }

            if ($role['name'] == 'School management') {
                $tiles['classes_analyses'] = array(
                    'menu'  => 'analyses',
                    'icon'  => 'analyse-klassen',
                    'title' => __("Klassen"),
                    'path'  => '/analyses/school_classes_overview'
                );

                $tiles['teachers'] = array(
                    'menu'  => 'analyses',
                    'icon'  => 'analyse-leraar',
                    'title' => __("Docenten"),
                    'path'  => '/analyses/teachers_overview'
                );
                $tiles['students'] = array(
                    'menu'  => 'analyses',
                    'icon'  => 'analyse-leerlingen',
                    'title' => __("Studenten"),
                    'path'  => '/analyses/students_overview'
                );

                $tiles['messages'] = array(
                    'menu'  => 'messages',
                    'icon'  => 'messages',
                    'title' => __("Berichten"),
                    'path'  => '/messages'
                );
            }
            if ($role['name'] == 'Support') {
                $tiles['teachers'] = array(
                    'menu'  => 'lists',
                    'icon'  => 'analyse-klassen',
                    'title' => __('Docenten'),
                    'path'  => '/users/index/teachers'
                );
                $tiles['students'] = array(
                    'menu'  => 'lists',
                    'icon'  => 'analyse-klassen',
                    'title' => __('Studenten'),
                    'path'  => '/users/index/students'
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
                    'errors' => ['new_password_mismatch' => __("Wachtwoorden komen niet overeen")]
                ]);
                die;
            }

            $user_id = AuthComponent::user('uuid');

            $result = json_decode($this->UsersService->resetPasswordForm($user_id, $data));

            if (isset($result->errors) && !empty($result->errors)) {
                return $this->formResponse(false, ['errors' => $result->errors]);
            }

            return $this->formResponse(true);
//            if ($result != '{"old_password":["Record does not match stored value"]}') {
//                $this->formResponse(true);
//            } else {
//                $this->formResponse(false, [
//                    'message' => __("Wachtwoorden komen niet overeen")
//                ]);
//            }
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

            $info['school_location_list'] = array_map(function ($location) use ($info) {
                return (object)[
                    'uuid'     => $location['uuid'],
                    'name'     => $location['name'],
                    'active'   => $location['active'],
                    'language' => $location['language'],
                ];
            }, $this->UsersService->getSchoolLocationList());
        }


        $info['laravel_look'] = $info['school_location']['allow_new_student_environment'];
        $info['isStudent'] = $student;
        $info['isTeacher'] = $teacher;
        $info['menu_taken_direct_link'] = $info['school_location']['feature_settings']['allow_new_taken_tests_page'] == '1' ? true : false;
        $info['enable_auto_logout'] = is_null($info['featureSettings']['enable_auto_logout']) || !!$info['featureSettings']['enable_auto_logout'];
        $info['auto_logout_minutes'] = $info['featureSettings']['auto_logout_minutes'] ?? 15;

        $return = [];
        $allowed = [
            'name_first',
            'name_suffix',
            'name',
            'abbriviation',
            'isTeacher',
            'isStudent',
            'school_location_list',
            'school_location_id',
            'guest',
            'laravel_look',
            'menu_taken_direct_link',
            'auto_logout_minutes',
            'enable_auto_logout',
        ];

        foreach ($allowed as $key) {
            $return[$key] = array_key_exists($key, $info) ? $info[$key] : '';
        }

        echo json_encode($return);
    }

    public function import(
        $type
    )
    {
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

    public function doImport(
        $type
    )
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

    public function doImportTeachersBare()
    {
        $data['data'] = $this->request->data;

        $result = $this->UsersService->doImportTeacherBare($data);

        if (!$result) {
            $this->formResponse(false, $this->UsersService->getErrors());
            return false;
        }
        $this->formResponse(true, []);
    }

    public function setActiveSchoolLocation(
        $uuid
    )
    {
//        if (! $this->isAuthorizedAs(['Teacher']) ) {
//            $this->formResponse(false, '');
//            return;
//        }

        $result = $this->UsersService->switchSchool($uuid);

        $this->refreshUserSessionData();

        if (!$result) {
            $this->formResponse(false, $this->UsersService->getErrors());
            return false;
        }
        $this->formResponse(true, $result);

    }

    public function add_existing_teachers()
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

    public function load_existing_teachers()
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

    public function add_existing_teacher_to_schoolLocation()
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

    public function delete_existing_teacher_from_schoolLocation()
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


    public function resendEmailVerificationMail()
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

    public function goToLaravelPath($path = null)
    {
        if ($this->request->is('post')) {
            $path = $this->data['path'];
            $autoLogout = $this->data['autoLogout'] ?? false;

            $params = [];
            if ($path === null) {
                $path = $this->request->query['path'];
            }

            if ($path[0] !== '/') {
                $path = '/' . $path;
            }

            if($this->data['extendUserSession']){
                $params['extensionTime'] = $this->data['extensionTime'];
            }

            if(!empty(CakeSession::read('support.id'))) {
                $params['support']['name'] = CakeSession::read('support.name');
                $params['support']['id'] = CakeSession::read('support.id');
            }

            $params['app_details'] = $this->getAppInfoFromSession();
            $responseData = $this->UsersService->createTemporaryLogin($params, $path);
            if ($autoLogout) {
                $this->logout();
            }
            return $this->formResponse(true, $responseData);
        }
    }

    public function temporary_login($tlid)
    {
        $result = $this->UsersService->getUserWithTlid($tlid);

        $result = $this->handleTemporaryLoginOptions($result);
        $this->Auth->login($result);

        try {
            $this->render('templogin', 'templogin');
        } catch (Exception $e) {
            echo '
                <html>
                    <head>
                        <meta http-equiv="refresh" content="0;url=/" />
                        <title><?= __("Een moment")?></title>
                    </head>
                    <body>
                        <?= __("Een moment...")?>
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
                    $service->getErrors()['error']) . __(" niet gevonden!"));
        }

        return $service->getErrors()['error'];
    }

    public function teacher_complete_user_import_main_school_class()
    {
        $this->isAuthorizedAs('Teacher');

        if ($this->request->is('put')) {
            $response = $this->SchoolClassesService->updateWithEductionLevelsForMainClasses($this->request->data);
            if ($response == false) {

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
                'is_main_school_class' => 1,
            ],
            'mode'   => 'import_data',
        ]);


        $eductionLevels = $this->SchoolLocationsService->getSchoolLocationEducationLevels(
            getUUID(AuthComponent::user('school_location'), 'get'),
            true
        );

        $schoolLvsType = $this->SchoolLocationsService->getLvsType(getUUID(AuthComponent::user('school_location'), 'get'))[0];

        $this->set('lvs_type', $schoolLvsType);
        $this->set('classes_list', $classesList);
        $this->set('education_levels', $eductionLevels);
    }

    public function teacher_complete_user_import_education_level_cluster_class()
    {
        $this->isAuthorizedAs('Teacher');

        if ($this->request->is('put')) {
            $response = $this->SchoolClassesService->updateWithEductionLevelsForClusterClasses($this->request->data);
            if ($response == false) {

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
                'demo'                 => 0,
            ],
            'mode'   => 'import_data',
        ]);

        $eductionLevels = $this->SchoolLocationsService->getSchoolLocationEducationLevels(
            getUUID(AuthComponent::user('school_location'), 'get'),
            true
        );

        $schoolLvsType = $this->SchoolLocationsService->getLvsType(getUUID(AuthComponent::user('school_location'), 'get'))[0];

        $this->set('lvs_type', $schoolLvsType);
        $this->set('classes_list', $classesList);
        $this->set('education_levels', $eductionLevels);
    }

    public function teacher_complete_user_import_subject_cluster_class()
    {
        $this->isAuthorizedAs('Teacher');

        if ($this->request->is('put')) {
            $response = $this->SchoolClassesService->updateTeachersWithSubjectsForClusterClasses($this->request->data);
            if ($response == false) {

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
        foreach ($teachersList as $teacherRecord) {
            if (!array_key_exists($teacherRecord['class_id'], $teacherEntries) || !is_array($teacherEntries[$teacherRecord['class_id']])) {
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
                'demo'    => 0,
            ],
            'mode'   => 'import_data',
        ]);
        $subjects = $this->TestsService->getSubjects(false, 'all', true, true, true);

        $schoolLvsType = $this->SchoolLocationsService->getLvsType(getUUID(AuthComponent::user('school_location'), 'get'))[0];

        $this->set('lvs_type', $schoolLvsType);
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

        $schoolLvsType = $this->SchoolLocationsService->getLvsType(getUUID(AuthComponent::user('school_location'), 'get'))[0];

        $this->set('lvs_type', $schoolLvsType);
        $this->set('classes_list', $classesList);
        $this->set('education_levels', $eductionLevels);
    }

    public function prevent_logout()
    {
        $this->set('opened_by_user', $this->params['url']['opened_by_user']);
    }

    public function terms_and_conditions($daysLeft)
    {
        $this->set('closeable', $daysLeft > 0);
    }

    public function new_features_popup()
    {
        $infos=$this->InfoService->features();
        $this->set('infos', $infos);
    }

    public function accept_general_terms()
    {
        $this->autoRender = false;
        $this->UsersService->setGeneralTermsLogAcceptedAt(getUUID(AuthComponent::user(), 'get'));
    }

    private function handleGeneralTermsForUser($userGeneralTermsLog = null)
    {
        $shouldDisplayGeneralTermsNotification =
            AuthComponent::user('school_location')['license_type'] !== 'CLIENT' 
            && $userGeneralTermsLog != null 
            && $userGeneralTermsLog['accepted_at'] == null;

        $this->set('shouldDisplayGeneralTermsNotification', $shouldDisplayGeneralTermsNotification);
        if ($shouldDisplayGeneralTermsNotification) {
            $firstRequest = new DateTime($userGeneralTermsLog['created_at']);

            $requestExpirationDate = $firstRequest->setTimezone(new DateTimeZone(Configure::read('Config.timezone')))->setTime(0, 0);
            $requestExpirationDate->add(new DateInterval('P14D'));

            $today = new DateTime('now');
            $today->setTime(0, 0);

            $generalTermsDaysLeft = $today->format('Y-m-d') < $requestExpirationDate->format('Y-m-d') ? $requestExpirationDate->diff($today)->format('%d') : 0;
            $this->set('generalTermsDaysLeft', $generalTermsDaysLeft);
        }
    }

    private function handleTrialPeriodForUser($trialPeriod = null)
    {
        $shouldDisplayTrialPeriodNotification = (
            AuthComponent::user('school_location')['license_type'] === 'TRIAL' &&
            $trialPeriod !== null
        );
        $this->set('shouldDisplayTrialPeriodNotification', $shouldDisplayTrialPeriodNotification);
        if (!$shouldDisplayTrialPeriodNotification) {
            return true;
        }
        [$daysLeft, $totalDays] = $this->calculateTrialDaysLeft($trialPeriod);
        $this->set('trialPeriodDaysLeft', $daysLeft);
        $this->set('trialPeriodTotalDays', $totalDays);
        $this->set('trialInfoURL', $this->trialInfoURL);
    }

    private function handleIfNewFeatureRelease()
    {
        $this->set('shouldShowIfNewFeature', CakeSession::read('Auth.User.shouldShowNewFeaturePopup'));
        $this->set('shouldShowIfNewFeatureMessageClosed', CakeSession::read('Auth.User.shouldShowNewFeatureMessage'));
    }

    private function handleTemporaryLoginOptions($result)
    {
        if (empty($result['temporaryLoginOptions'])) {
            return $result;
        }
        $options = $result['temporaryLoginOptions'];
        unset($result['temporaryLoginOptions']);
        $optionsSupport = json_decode($options);
        if (array_key_exists('support', $optionsSupport)) {
            CakeSession::write('support',
                [
                    'id'=>$optionsSupport->support->id,
                    'name' => $optionsSupport->support->name
                ]
            );
        }elseif(!empty(CakeSession::read('support.id'))) {
            CakeSession::delete('support');
        }

        CakeSession::write('temporaryLoginOptions', $options);

        return $result;
    }

    public function front_controller()
    {
        $this->autoRender = false;

        $headers = AppVersionDetector::getAllHeaders();
        $this->handleHeaderCheck($headers);

        if (CakeSession::read('temporaryLoginOptions')) {
            $options = json_decode(CakeSession::read('temporaryLoginOptions'), true);
            HelperFunctions::setReturnRouteForLaravel();
            CakeSession::delete('temporaryLoginOptions');
            $internalPage = null;
            if (array_key_exists('page', $options)) {
                $internalPage = $options['page'];
            } else if (array_key_exists('internal_page', $options)) {
                $internalPage = $options['internal_page'];
            }
    
            if(array_key_exists('page_number', $options)) {
                CakeSession::write('page_number', $options['page_number']);
            }


            if($internalPage){
                $internalPage = substr($internalPage, 0, 1) === '/' ? $internalPage : '/'.$internalPage;
                $this->set('internal_page',$internalPage);
                $pageAction = null;
                if(array_key_exists('page_action',$options)){
                    $pageAction = $options['page_action'];
                }

                $this->set('page_action',$pageAction);
                $this->render('internal_redirect');
            }
        } else {
            $this->welcome();
        }

    }

    public function return_to_laravel($logout = false)
    {
        $this->autoRender = false;

        $returnUrl = $this->returnToLaravelUrl(getUUID(AuthComponent::user(), 'get'));

        if ($logout) {
            $this->Auth->logout();
            $this->Session->destroy();
        }

        return $returnUrl['url'];
    }

    public function pusher_auth()
    {
        $this->autoRender = false;
        $requestData = $this->request->data;

        $app_id = ''; //not necessary
        $app_key = Configure::read('pusher-key');
        $app_secret = Configure::read('pusher_surveillance_key');
        $app_cluster = 'eu';

        $pusher = new Pusher($app_key, $app_secret, $app_id, ['cluster' => $app_cluster]);

        if (strpos($requestData['channel_name'], 'CoLearning') > 0) {
            $presence_data = [
                'user_uuid'            => AuthComponent::user('uuid'),
                'testparticipant_uuid' => '',
                'student'              => $this->UsersService->hasRole('Student'),
            ];

            return $pusher->presence_auth($requestData['channel_name'], $requestData['socket_id'], AuthComponent::user('id'), $presence_data);
        }
        if (strpos($requestData['channel_name'], 'presence') === 0) {
            $presence_data = [
                'name'    => AuthComponent::user('name'),
                'uuid'    => AuthComponent::user('uuid'),
                'guest'   => AuthComponent::user('guest'),
                'student' => $this->UsersService->hasRole('Student'),
            ];

            return $pusher->presence_auth($requestData['channel_name'], $requestData['socket_id'], AuthComponent::user('id'), $presence_data);
        }

        return true;
    }

    public function get_laravel_login_page()
    {
        $this->autoRender = false;

        $appInfo = $this->getAppInfoFromSession();
        $response = $this->UsersService->getLaravelLoginPage();
        if ($appInfo['TLCOs'] == 'iOS') {
            $response['url'] = $response['url'].'?device=ipad';
        }

        return $response['url'];
    }

    public function getAppDetailsForMenu()
    {
        $this->autoRender = false;
        return json_encode(AppVersionDetector::detect() + ['status' => AppVersionDetector::isVersionAllowed()]);
    }

    public function change_trial_date($userUuid, $userTrailPeriodUuid)
    {
        if ($this->request->is('post')) {
            $params = [
                'date'     => $this->request->data['User']['date'],
                'user_trial_period_uuid' => $userTrailPeriodUuid
            ];
            $response = $this->UsersService->updateTrialPeriod($userUuid, $params);

            $this->formResponse(true, $response);
        }
    }

    private function calculateTrialDaysLeft($trialPeriod): array
    {
        $today = new DateTime('now');
        $today->setTime(0, 0);

        $startDate = new DateTime($trialPeriod['created_at']);
        $startDate->setTime(0, 0);
        $startDate->setTimezone(new DateTimeZone(Configure::read('Config.timezone')));

        $expirationDate = new DateTime($trialPeriod['trial_until']);
        $expirationDate->setTimezone(new DateTimeZone(Configure::read('Config.timezone')));

        $daysLeft = $today->format('Y-m-d') < $expirationDate->format('Y-m-d') ? $expirationDate->diff($today)->days : 0;
        $totalDays = $startDate->diff($expirationDate)->days;

        return [$daysLeft, $totalDays];
    }

    private function getTrialPeriodStatusses($users): array
    {
        $trialStatus = [];
        $trialDaysLeft = [];
        foreach($users as $user) {
            if (empty($user['trial_periods'])) {
                $trialStatus[getUUID($user, 'get')] = 'not_started';
                continue;
            }
            foreach($user['trial_periods'] as $trialPeriod) {
                $lookupKey = sprintf('%s-%s', getUUID($user, 'get'), getUUID($trialPeriod, 'get'));
                [$daysLeft, $totalDays] = $this->calculateTrialDaysLeft($trialPeriod);
                if ($daysLeft <= 0) {
                    $trialStatus[$lookupKey] = 'expired';
                    continue;
                }
                $trialStatus[$lookupKey] = 'active';
                $trialDaysLeft[$lookupKey] = $daysLeft;
            }

        }
        return [$trialStatus, $trialDaysLeft];
    }

    public function trial_period_ended($closeable, $multipleSchoolLocations)
    {
        $this->set('hasMultipleLocations', $multipleSchoolLocations === 'true');
        $this->set('trialInfoURL', $this->trialInfoURL);
        $this->set('closeable', $closeable);
    }

    private function canTeacherBeExamCoordinator($userUuid)
    {
        $classes = $this->SchoolClassesService->getForUser($userUuid);
        foreach($classes as $class) {
            if (!!$class['demo'] === false) {
                return false;
            }
        }

        return true;
    }

    private function refreshUserSessionData()
    {
        $this->Session->write(
            'Auth.User',
            $this->UsersService->getUser(AuthComponent::user()['uuid'])
        );
    }

    public function switch_school_location_popup()
    {
        
    }

    /**
     * Set the chat opened state in the session
     * 
     * @param bool $isChatOpened
     * @return bool
     */
    public function setIsChatOpened($isChatOpened = false)
    {
        $this->autoRender = false;
        return $this->Session->write('Menu.isChatOpened', $isChatOpened);
    }

    /**
     * Get the chat opened state from the session
     * 
     * @return bool
     */
    public function getIsChatOpened()
    {
        $this->autoRender = false;
        return $this->Session->read('Menu.isChatOpened') ?? false;
    }
}
