<?php

App::uses('AppController', 'Controller');
App::uses('FileManagementService', 'Lib/Services');
App::uses('SchoolLocationsService', 'Lib/Services');
App::uses('UsersService', 'Lib/Services');
App::uses('TestsService', 'Lib/Services');

class FileManagementController extends AppController
{
    public function beforeFilter()
    {
        $this->FileService = new FileManagementService();
        $this->SchoolLocationService = new SchoolLocationsService();
        $this->UsersService = new UsersService();
        $this->TestsService = new TestsService();

        parent::beforeFilter();
    }

    protected function ifNotAllowedExit($allowed, $checkforToetsenbakker = false){
        if(!$this->UsersService->hasRole($allowed)){
            exit;// no access one gets here by trying;
        }
        if($checkforToetsenbakker){
            if($this->UsersService->hasRole('Teacher') && !AuthComponent::user('isToetsenbakker')){
                exit; // one should be a toetsenbakker;
            }
        }
    }

    public function update($id,$type = 'classupload'){
        $this->ifNotAllowedExit(['Teacher','Account manager'], true);
        $params = $this->request->data;

        $params['type'] = $type;

//        if($type == 'classupload'){
//            if($params['file_management_status_id'] != 3) {
//                $params['handledby'] = AuthComponent::user('id');
//            }
//        }

        if($this->FileService->update($id,$params)){
            echo "1";
        }
        else{
            throw new NotFoundException();
        }
        exit;
    }

    public function update_status($id, $type = 'classupload'){
        $this->ifNotAllowedExit(['Teacher','Account manager'], true);
        $school_location_id = AuthComponent::user('school_location_id');
        $params = [
            'type' => $type,
        ];
        switch($this->request->data['action']){
            case 'close':
                $params['file_management_status_id'] = 3;
                break;
            case 'claim':
            default:
                $params['file_management_status_id'] = 2;
                $params['handledby'] = AuthComponent::user('id');
                break;
        }

        if($this->FileService->update($id,$params)){
            echo "1";
        }
        else{
            throw new NotFoundException();
        }
        exit;
    }

    public function download($id, $type = 'classupload'){
        $this->ifNotAllowedExit(['Teacher','Account manager'], true);
//        $school_location_id = AuthComponent::user('school_location_id');

        $params = [
            'type' => $type,
        ];

        $data = $this->FileService->getItem($id,$params);
        if(!$data) exit;
        $download = $this->FileService->getDownload($id);

        $this->response->body($download);
        $this->response->header('Content-Disposition', 'attachment; filename='.$data['name']);
        return $this->response;
    }


    public function view_testupload($id){
        $this->ifNotAllowedExit(['Teacher','Account manager'], true);

        $params = [
            'type' => 'testupload'
        ];

        $data = $this->FileService->getItem($id,$params);

        $this->set('file', $data);

        $testKinds = $this->TestsService->getKinds();
        foreach($testKinds as $id => $name){
            if($id == $data['typedetails']['test_kind_id']){
                $this->set('testKind',$name);
            }
        }
        $schoolLocationEducationLevels = $this->SchoolLocationService->getSchoolLocationEducationLevels($data['school_location_id']);
        if(!$schoolLocationEducationLevels){
            $this->set('error',implode('<br />',$this->SchoolLocationService->getErrors()));
        }
        else {
            $educationLevel = '';
            foreach ($schoolLocationEducationLevels as $el) {
                if($el['education_level']['id'] == $data['typedetails']['education_level_id']) {
                    $educationLevel = $el['education_level']['name'];
                }
            }
            $this->set('educationLevel', $educationLevel);
        }
        $view = 'view_testupload_toetsenbakker';

        if(!AuthComponent::user('isToetsenbakker')) {
            $view = 'view_testupload';
            $params = [
                'mode' => 'all',
                'filter' => [
                    'school_location_id' => $data['school_location_id'],
                    'role' => [1],
                ],
            ];


            $_teachers = $this->UsersService->getUsers($params);

            $teachers = [];
            foreach ($_teachers as $teacher) {
                $teachers[$teacher['id']] = sprintf('%s %s %s', $teacher['name_first'], $teacher['name_suffix'], $teacher['name']);
            }

            $this->set('teachers', $teachers);

            $params = [
                'mode' => 'all',
                'filter' => [
                    'school_location_id' => $data['school_location_id'],
                    'role' => [6],
                ],
            ];

            $schoolbeheerders = $this->UsersService->getUsers($params);

            $this->set('schoolbeheerders',$schoolbeheerders);
        }

        $this->render($view);
    }

    public function testuploads(){
        $this->ifNotAllowedExit(['Teacher','Account manager'], false);
        $view = 'testuploads';
        if($this->UsersService->hasRole('Account manager')){
            $view = 'testuploads_accountmanager';
        }
        else if(AuthComponent::user('isToetsenbakker')){
            $view = 'testuploads_toetsenbakker';
        }
        $this->render($view);
    }

    public function load_testuploads(){
        $this->ifNotAllowedExit(['Teacher','Account manager'], false);
        $params = $this->request->data;
        $params['type'] = 'testupload';

        $data = $this->FileService->getData($params);

        $files = [];
        foreach($data['data'] as $file){
            $files[] = $file;
        }

        $this->set('files', $files);

        $view = 'load_testuploads';
        if($this->UsersService->hasRole('Account manager')){
            $view = 'load_testuploads_accountmanager';
        }
        else if(AuthComponent::user('isToetsenbakker')){
            $view = 'load_testuploads_toetsenbakker';
        }

        $this->render($view);
    }

    public function upload_test() {
        $this->ifNotAllowedExit(['Teacher'], false);
        $school_location_id = AuthComponent::user('school_location_id');

        if($this->request->is('post')) {

            $data = $this->request->data['FileTest'];
            $error = false;

            if(!isset($data['education_level_id'])){
                $response = 'Het is niet duidelijk om welk niveau het gaat';
                $error = true;
            }
            else if(!isset($data['education_level_year'])){
                $response = 'Het is niet duidelijk om welk leerjaar het gaat';
                $error = true;
            }
            else if(!isset($data['test_kind_id'])){
                $response = "het is niet duidelijk om wat voor type toets het gaat";
                $error = true;
            }
            else if(!isset($data['name'])){
                $response = "het is niet duidelijk om wat de naam van de toets is";
                $error = true;
            }
            else if(!isset($data['file']) || count($data['file']) < 1){
                $response = 'File(s) niet gevonden om te uploaden, probeer het nogmaals';
                $error = true;
            }else{
                foreach($data['file'] as $file){
                    if(!isset($file['tmp_name']) || !$file['tmp_name']){
                        $response = 'File(s) niet gevonden om te uploaden, probeer het nogmaals';
                        $error = true;
                    }
                }
                if(!$error) {
                    $r = $this->FileService->uploadTest($school_location_id, $data);
                    if($r === false){
                        $response = 'Het is helaas niet gelukt om de upload te verwerken probeer het nogmaals';
                        $error = true;
                    }
                    else if (array_key_exists('error', $r)) {
                        $response = $r['error'];
                        $error = true;
                    } else {
                        $response = "De toets is klaargezet voor verwerking";
                    }
                }
            }

            if($error){
                echo "
                <script>
                    window.parent.handleUploadError('".$response."');
                </script>
                ";
            }
            else {

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
        if(!$schoolLocationEducationLevels){
            $this->set('error',implode('<br />',$this->SchoolLocationService->getErrors()));
        }
        else{
            $elAr = [];
            $eloAr = [];
            foreach($schoolLocationEducationLevels as $el){
                $elAr[] = [
                    'name' => $el['education_level']['name'],
                    'id' => $el['education_level']['id'],
                    'max_years' => $el['education_level']['max_years']
                ];
                $eloAr[$el['education_level']['id']] = $el['education_level']['name'];
            }
            $this->set('educationLevels',$elAr);
            $this->set('educationLevelOptions',$eloAr);
        }
        $testKinds = $this->TestsService->getKinds();
        if(!$schoolLocationEducationLevels){
            $this->set('error',implode('<br />',$this->SchoolLocationService->getErrors()));
        }
        else{
            $this->set('testKindOptions',$testKinds);
        }
    }


    /**
     * START of Class Related upload methods
     */

    public function view_classupload($id){
        $this->ifNotAllowedExit(['Account manager'], false);
        $params = [
            'type' => 'classupload'
        ];

        $data = $this->FileService->getItem($id,$params);


        $this->set('file', $data);

        $schoolLocationEducationLevels = $this->SchoolLocationService->getSchoolLocationEducationLevels($data['school_location_id']);

        if(!$schoolLocationEducationLevels){
            $this->set('error',implode('<br />',$this->SchoolLocationService->getErrors()));
        }
        else {
            $educationLevel = '';
            foreach ($schoolLocationEducationLevels as $el) {
                if($el['education_level']['id'] == $data['typedetails']['education_level_id']) {
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

        $this->set('schoolbeheerders',$schoolbeheerders);

    }

    public function load_classuploads(){
        $this->ifNotAllowedExit(['Teacher','Account manager'], false);
        $params = $this->request->data;
        $params['type'] = 'classupload';

        $view = 'load_classuploads';
        if($this->UsersService->hasRole('Account manager')){
            $view = 'load_classuploads_accountmanager';
        }

        $data = $this->FileService->getData($params);

        $this->set('files', $data['data']);

        $this->render($view);
    }

    public function classuploads()
    {
        $this->ifNotAllowedExit(['Teacher','Account manager'], false);
        $view = 'classuploads';
        if($this->UsersService->hasRole('Account manager')){
            $view = 'classuploads_accountmanager';
        }
        $this->render($view);
    }

    public function upload_class() {
        $this->ifNotAllowedExit(['Teacher'], false);
        $school_location_id = AuthComponent::user('school_location_id');

        if($this->request->is('post')) {

            $data = $this->request->data['FileClass'];
            $error = false;

            if(!$data['class'] || strlen($data['class']) < 2){
                $response = 'Het is niet duidelijk om welke klas deze upload gaat.';
                $error = true;
            }
            else if(!isset($data['file']) || !isset($data['file']['tmp_name']) || !$data['file']['tmp_name']){
                $response = 'File niet gevonden om te uploaden, probeer het nogmaals';
                $error = true;
            }else{
                $r = $this->FileService->uploadClass($school_location_id, $data);

                if(array_key_exists('error',$r)){
                    $response = $r['error'];
                    $error = true;
                }
                else{
                    $response = "De klas is klaargezet voor verwerking";
                }
            }

            if($error){
                echo "
                <script>
                    window.parent.handleUploadError('".$response."');
                </script>
                ";
            }
            else {

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
        if(!$schoolLocationEducationLevels){
            $this->set('error',implode('<br />',$this->SchoolLocationService->getErrors()));
        }
        else{
            $elAr = [];
            $eloAr = [];
            foreach($schoolLocationEducationLevels as $el){
                $elAr[] = [
                    'name' => $el['education_level']['name'],
                    'id' => $el['education_level']['id'],
                    'max_years' => $el['education_level']['max_years']
                ];
                $eloAr[$el['education_level']['id']] = $el['education_level']['name'];
            }
            $this->set('educationLevels',$elAr);
            $this->set('educationLevelOptions',$eloAr);
        }
    }
}