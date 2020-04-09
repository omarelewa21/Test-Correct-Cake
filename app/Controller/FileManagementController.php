<?php

App::uses('AppController', 'Controller');
App::uses('FileManagementService', 'Lib/Services');
App::uses('SchoolLocationsService', 'Lib/Services');
App::uses('UsersService', 'Lib/Services');

class FileManagementController extends AppController
{
    public function beforeFilter()
    {
        $this->FileService = new FileManagementService();
        $this->SchoolLocationService = new SchoolLocationsService();
        $this->UsersService = new UsersService();

        parent::beforeFilter();
    }

    public function update($id,$type = 'classupload'){
        $params = $this->request->data;

        $params['type'] = $type;

        if($type == 'classupload'){
            if($params['file_management_status_id'] != 3) {
                $params['handledby'] = AuthComponent::user('id');
            }
        }

        if($this->FileService->update($id,$params)){
            echo "1";
        }
        else{
            throw new NotFoundException();
        }
        exit;
    }

    public function update_status($id, $type = 'classupload'){
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

    public function view_classupload($id){
        $params = [
            'type' => 'classupload'
        ];

        $data = $this->FileService->getItem($id,$params);

        $typeDetails = json_decode($data['typedetails']);

        $this->set('file', $data);
        $this->set('typedetails',$typeDetails);

        $schoolLocationEducationLevels = $this->SchoolLocationService->getSchoolLocationEducationLevels($data['school_location_id']);
        if(!$schoolLocationEducationLevels){
            $this->set('error',implode('<br />',$this->SchoolLocationService->getErrors()));
        }
        else {
            $educationLevel = '';
            foreach ($schoolLocationEducationLevels as $el) {
                if($el['education_level']['id'] == $typeDetails->education_level_id) {
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
        $view = 'classuploads';
        if($this->UsersService->hasRole('Account manager')){
            $view = 'classuploads_accountmanager';
        }
        $this->render($view);
    }

    public function upload_class() {
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
                    $response = "
                        <script>
                            Popup.closeLast();
                            Notify.notify('De klas is klaargezet voor verwerking','info');
                        </script>             
                     ";
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