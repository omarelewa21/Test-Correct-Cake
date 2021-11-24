<?php

App::uses('AppController', 'Controller');
App::uses('FileManagementService', 'Lib/Services');
App::uses('SchoolLocationsService', 'Lib/Services');
App::uses('UsersService', 'Lib/Services');
App::uses('TestsService', 'Lib/Services');
App::uses('HelperFunctions', 'Lib');

class FileManagementController extends AppController {

    public function beforeFilter() {
        $this->FileService = new FileManagementService();
        $this->SchoolLocationService = new SchoolLocationsService();
        $this->UsersService = new UsersService();
        $this->TestsService = new TestsService();

        parent::beforeFilter();
    }

    protected function ifNotAllowedExit($allowed, $checkforToetsenbakker = false) {
        $this->isAuthorizedAs($allowed);

        if ($checkforToetsenbakker) {
            if ($this->UsersService->hasRole('Teacher') && !AuthComponent::user('isToetsenbakker')) {
                exit; // one should be a toetsenbakker;
            }
        }
    }

    public function update($id, $type = 'classupload') {
        $this->ifNotAllowedExit(['Teacher', 'Account manager'], true);
        $params = $this->request->data;

        $params['type'] = $type;

//        if($type == 'classupload'){
//            if($params['file_management_status_id'] != 3) {
//                $params['handledby'] = AuthComponent::user('id');
//            }
//        }

        if ($this->FileService->update($id, $params)) {
            echo "1";
        } else {
            throw new NotFoundException();
        }
        exit;
    }

    public function update_status($id, $type = 'classupload') {
        $this->ifNotAllowedExit(['Teacher', 'Account manager'], true);
        $school_location_id = AuthComponent::user('school_location_id');
        $params = [
            'type' => $type,
        ];
        switch ($this->request->data['action']) {
            case 'close':
                $params['file_management_status_id'] = 3;
                break;
            case 'claim':
            default:
                $params['file_management_status_id'] = 2;
                $params['handledby'] = AuthComponent::user('id');
                break;
        }

        if ($this->FileService->update($id, $params)) {
            echo "1";
        } else {
            throw new NotFoundException();
        }
        exit;
    }

    public function download($id, $type = 'classupload') {
        $this->ifNotAllowedExit(['Teacher', 'Account manager'], true);
//        $school_location_id = AuthComponent::user('school_location_id');

        $params = [
            'type' => $type,
        ];

        $data = $this->FileService->getItem($id, $params);
        if (!$data)
            exit;
        $download = $this->FileService->getDownload($id);

        $this->response->body($download);
        $this->response->header('Content-Disposition', 'attachment; filename=' . $data['name']);
        return $this->response;
    }

    public function view_testupload($id) {
        $this->ifNotAllowedExit(['Teacher', 'Account manager'], true);

        $params = [
            'type' => 'testupload'
        ];

        $data = $this->FileService->getItem($id, $params);

        $this->set('file', $data);

        $testKinds = $this->TestsService->getKinds();
        foreach ($testKinds as $id => $name) {
            if ($id == $data['typedetails']['test_kind_id']) {
                $this->set('testKind', $name);
            }
        }
        $schoolLocationEducationLevels = $this->SchoolLocationService->getSchoolLocationEducationLevels(getUUID($data['school_location'], 'get'));
        if (!$schoolLocationEducationLevels) {
            $this->set('error', implode('<br />', $this->SchoolLocationService->getErrors()));
        } else {
            $educationLevel = '';
            foreach ($schoolLocationEducationLevels as $el) {
                if ($el['education_level']['id'] == $data['typedetails']['education_level_id']) {
                    $educationLevel = $el['education_level']['name'];
                }
            }
            $this->set('educationLevel', $educationLevel);
        }
        $view = 'view_testupload_toetsenbakker';

        if (!AuthComponent::user('isToetsenbakker')) {
            $view = 'view_testupload';
            $params = [
                'mode' => 'all',
                'filter' => [
                    'school_location_id' => $data['school_location_id'],
                    'role' => [1],
                ],
            ];


            $_teachers = $this->UsersService->getUsers($params);

            $teachers = $_teachers;
//            foreach ($_teachers as $teacher) {
//                $teachers[getUUID($teacher, 'get')] = [
//                    'name' => sprintf('%s %s %s', $teacher['name_first'], $teacher['name_suffix'], $teacher['name']),
//
//                ];
//            }
//
            $this->set('teachers', $teachers);

            $params = [
                'mode' => 'all',
                'filter' => [
                    'school_location_id' => $data['school_location_id'],
                    'role' => [6],
                ],
            ];

            $schoolbeheerders = $this->UsersService->getUsers($params);

            $this->set('schoolbeheerders', $schoolbeheerders);
        }

        $this->render($view);
    }

    public function testuploads() {
        $this->ifNotAllowedExit(['Teacher', 'Account manager'], false);
        $view = 'testuploads';
        if ($this->UsersService->hasRole('Account manager')) {
            $view = 'testuploads_accountmanager';
        } else if (AuthComponent::user('isToetsenbakker')) {
            $view = 'testuploads_toetsenbakker';
        }
        $this->render($view);
    }

    public function load_testuploads($params = []) {

        $this->ifNotAllowedExit(['Teacher', 'Account manager'], false);
        $params = $this->request->data;

        $params['filter']['type'] = 'testupload';

        $data = $this->FileService->getData($params);

        $this->log('load_testuploads', 'debug');
        $this->log($data, 'debug');
        $this->log($params, 'debug');

        $files = [];
        foreach ($data['data'] as $file) {
            $files[] = $file;
        }

        $this->set('files', $files);

        $this->log($files, 'debug');

        $view = 'load_testuploads';

        if ($this->UsersService->hasRole('Account manager')) {
            $view = 'load_testuploads_accountmanager';
        } else if (AuthComponent::user('isToetsenbakker')) {
            $view = 'load_testuploads_toetsenbakker';
        }

        $this->render($view);
    }

    public function upload_test_filepond() {

        if ($this->request->is('post')) {

            echo json_encode($this->request->data['FileTest']);
        }

        //$data = $this->request->data['FileTest'];
        //return json_encode($data);
    }

    public function upload_test() {

        $this->ifNotAllowedExit(['Teacher'], false);

        $this->blockWithModalIfRegistrationNotCompletedAndInTestSchool();

        $school_location_id = getUUID(AuthComponent::user('school_location'), 'get');

        if ($this->request->is('post')) {

            $data = $this->request->data['FileTest'];
            // capture filepond filesubmit
            If (!isset($data['education_level_id'])) {

                $filepond_data = json_decode($_POST['data']['FileTest']['file'][0],1);
                // add filepond data
                $data['form_id']=$filepond_data['form_id'];

                $r = $this->FileService->uploadTest($school_location_id, $data);

                if (array_key_exists('error', $r)) {
                    $response = $r['error'];
                    $error = true;
                } else {
                    $response = __("De klas is klaargezet voor verwerking");
                }

                if ($error) {
                    echo "
                    <script>
                        window.parent.handleUploadError('" . $response . "');
                    </script>
                    ";
                } else {

                    echo "
                        <div id='response'>" . $response . "</div>
                        <script>
                            window.parent.handleUploadResponse(document.getElementById('response').outerHTML);
                        </script>
                    ";
                }

                return true;

            } else {
                $this->log('no filepond data', 'debug');
            }

            $data['file'] = [];
            // useless checks
            $error = false;
            if (!isset($data['education_level_id'])) {
                $response = __("Het is niet duidelijk om welk niveau het gaat");
                $error = true;
            } else if (!isset($data['education_level_year'])) {
                $response = __("Het is niet duidelijk om welk leerjaar het gaat");
                $error = true;
            } else if (!isset($data['test_kind_id'])) {
                $response = __("het is niet duidelijk om wat voor type toets het gaat");
                $error = true;
            } else if (!isset($data['name'])) {
                $response = __("het is niet duidelijk om wat de naam van de toets is");
                $error = true;
            } else if (!isset($data['correctiemodel']) || $data['correctiemodel'] != 1) {
                $response = __("Er dient een correctiemodel mee gestuurd te worden");
                $error = true;
            } else if (!isset($data['multiple']) || $data['multiple'] != 0) {
                $response = __("Er kan maximaal 1 toets per keer geupload worden");
                $error = true;
            } else {
                if (!$error) {
                    $r = $this->FileService->uploadTest($school_location_id, $data);

                    if ($r === false) {
                        $response = __("Het is helaas niet gelukt om de upload te verwerken probeer het nogmaals");
                        $error = true;
                    } else if (array_key_exists('error', $r)) {
                        $response = $r['error'];
                        $error = true;
                    } else {
                        $response = __("De toets is klaargezet voor verwerking");
                    }
                }
            }

            if ($error) {
                echo "
                <script>
                    window.parent.handleUploadError('" . $response . "');
                </script>
                ";
            } else {

                echo "
                    <div id='response'>" . $response . "</div>
                    <script>
                        window.parent.handleUploadResponse(document.getElementById('response').outerHTML);
                    </script>
                ";
            }

            exit;
        }

        // no post
        $schoolLocationEducationLevels = $this->SchoolLocationService->getSchoolLocationEducationLevels($school_location_id);
        if (!$schoolLocationEducationLevels) {
            $this->set('error', implode('<br />', $this->SchoolLocationService->getErrors()));
        } else {
            $elAr = [];
            $eloAr = [];
            foreach ($schoolLocationEducationLevels as $el) {
                $elAr[] = [
                    'name' => $el['education_level']['name'],
                    'id' => getUUID($el['education_level'], 'get'),
                    'max_years' => $el['education_level']['max_years']
                ];
                $eloAr[getUUID($el['education_level'], 'get')] = $el['education_level']['name'];
            }
            $this->set('educationLevels', $elAr);
            $this->set('educationLevelOptions', $eloAr);
        }
        $testKinds = $this->TestsService->getKinds();
        if (!$schoolLocationEducationLevels) {
            $this->set('error', implode('<br />', $this->SchoolLocationService->getErrors()));
        } else {
//            for($i = 1; $i < count($testKinds)+1; $i++){
//                $testKinds[$i] = __($testKinds[$i]);
//            }

            foreach($testKinds as $key=> $kind){
                $testKinds[$key] = __($kind);
            }
            $this->set('testKindOptions', $testKinds);
        }
        if(array_key_exists('content_creation_step',$this->params['url']) && $this->params['url']['content_creation_step'] == 2) {
            $this->set('opened_from_content', true);
        } else {
            $this->set('opened_from_content', false);
        }
        $maxFileUpload = HelperFunctions::getInstance()->getMaxFileUploadSize();
        $this->set('max_file_upload_size', $maxFileUpload);
        $this->set('readable_max_upload_size', HelperFunctions::getInstance()->formatBytes($maxFileUpload));
        //$this->set('form_id', md5(time()));
        $this->set('form_id', $this->FileService->getFormId());
        $this->set('school_location_id', $school_location_id);
        $this->set('user_uuid', AuthComponent::user('uuid'));
    }

    /**
     * START of Class Related upload methods
     */
    public function view_classupload($id) {
        $this->ifNotAllowedExit(['Account manager'], false);
        $params = [
            'type' => 'classupload'
        ];

        $data = $this->FileService->getItem($id, $params);
        // for($i = 0; $i < sizeof($data); $i++){
        //     $data['status'][$i]['name'] = __($data['status'][$i]['name']);
        // }


        $this->set('file', $data);

        $schoolLocationEducationLevels = $this->SchoolLocationService->getSchoolLocationEducationLevels(getUUID($data['school_location'], 'get'));

        if (!$schoolLocationEducationLevels) {
            $this->set('error', implode('<br />', $this->SchoolLocationService->getErrors()));
        } else {
            $educationLevel = '';
            foreach ($schoolLocationEducationLevels as $el) {
                if ($el['education_level']['id'] == $data['typedetails']['education_level_id']) {
                    $educationLevel = $el['education_level']['name'];
                }
            }
            $this->set('educationLevel', $educationLevel);
        }

        $params = [
            'mode' => 'all',
            'filter' => [
                'school_location_id' => $data['school_location_id'],
                'role' => [6],
            ],
        ];

        $schoolbeheerders = $this->UsersService->getUsers($params);

        $this->set('schoolbeheerders', $schoolbeheerders);
    }

    public function load_classuploads() {
        $this->ifNotAllowedExit(['Teacher', 'Account manager'], false);
        $params = $this->request->data;
        $params['filter']['type'] = 'classupload';

        $view = 'load_classuploads';
        if ($this->UsersService->hasRole('Account manager')) {
            $view = 'load_classuploads_accountmanager';
        }
        $data = $this->FileService->getData($params);

        $this->log($data, 'debug');

        $this->set('files', $data['data']);

        $this->render($view);
    }

    public function classuploads() {
        $this->ifNotAllowedExit(['Teacher', 'Account manager'], false);
        $view = 'classuploads';
        if ($this->UsersService->hasRole('Account manager')) {
            $view = 'classuploads_accountmanager';
        }
        $this->render($view);
    }

    public function upload_class() {
        $this->ifNotAllowedExit(['Teacher'], false);
        $this->blockWithModalIfRegistrationNotCompletedAndInTestSchool();

        $school_location_id = getUUID(AuthComponent::user('school_location'), 'get');

        if ($this->request->is('post')) {

            $data = $this->request->data['FileClass'];
            $error = false;

            if (!$data['class'] || strlen($data['class']) < 2) {
                $response = __("Het is niet duidelijk om welke klas deze upload gaat.");
                $error = true;
            } else if (!isset($data['file']) || !isset($data['file']['tmp_name']) || !$data['file']['tmp_name']) {
                $response = __("File niet gevonden om te uploaden, probeer het nogmaals");
                $error = true;
            } else {
                $r = $this->FileService->uploadClass($school_location_id, $data);

                if (array_key_exists('error', $r)) {
                    $response = $r['error'];
                    $error = true;
                } else {
                    $response = __("De klas is klaargezet voor verwerking");
                }
            }

            if ($error) {
                echo "
                <script>
                    window.parent.handleUploadError('" . $response . "');
                </script>
                ";
            } else {

                echo "
                    <div id='response'>" . $response . "</div>
                    <script>
                        window.parent.handleUploadResponse(document.getElementById('response').outerHTML);
                    </script>
                ";
            }

            exit;
        }

        // no post
        $schoolLocationEducationLevels = $this->SchoolLocationService->getSchoolLocationEducationLevels($school_location_id);
        if (!$schoolLocationEducationLevels) {
            $this->set('error', implode('<br />', $this->SchoolLocationService->getErrors()));
        } else {
            $elAr = [];
            $eloAr = [];
            foreach ($schoolLocationEducationLevels as $el) {
                $elAr[] = [
                    'name' => $el['education_level']['name'],
                    'uuid' => getUUID($el['education_level'], 'get'),
                    'max_years' => $el['education_level']['max_years']
                ];
                $eloAr[getUUID($el['education_level'], 'get')] = $el['education_level']['name'];
            }
            $this->set('educationLevels', $elAr);
            $this->set('educationLevelOptions', $eloAr);
        }
    }

    private function blockWithModalIfRegistrationNotCompletedAndInTestSchool() {
        $userUuid = AuthComponent::user('uuid');
        $userId = AuthComponent::user('id');
        if (AuthComponent::user('is_temp_teacher')) {
            $result = ($this->UsersService->registrationNotCompletedForRegisteredNewTeacher($userUuid));
            if ($result['status'] == 'true') {
                if ($this->request->is('post')) {
                    $response = $this->UsersService->updateRegisteredNewTeacher(
                            $this->request->data['User'], $userUuid
                    );
                    $result = (json_decode($response));

                    if (property_exists($result, 'errors') && count((array) $result->errors) > 0) {
                        $this->formResponse(false, $result);
                    } else {
                        $this->formResponse(true, ['data' => $response]);
                    }
                    exit();
                }
                $data = $this->UsersService->getRegisteredNewTeacherByUserId($userUuid);

                $this->set('user', (object) $data);
                $this->set('in_app', true);
                echo $this->render('/Users/register_new_teacher', 'ajax');
                exit;
            } else {
                $response = $this->UsersService->notifySupportTeacherInDemoSchoolTriesToUpload(
                        $userUuid
                );

                echo $this->render(
                        '/Users/block_upload_when_in_test_school_modal', 'ajax'
                );
                exit;
            }
        }
    }

}
