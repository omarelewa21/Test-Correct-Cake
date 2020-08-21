<?php

App::uses('AppController', 'Controller');
App::uses('QtiImportService', 'Lib/Services');

class QtiimportController extends AppController
{
    public function beforeFilter()
    {
        $this->QtiImportService = new QtiImportService();

        parent::beforeFilter();
    }

    public function index()
    {
        $this->isAuthorizedAs(['Administrator', 'Account manager', 'School manager', 'School management']);

        $data = $this->QtiImportService->getData();
        
        $schoolList = [];
        foreach($data['schoolLocations'] as $loc){
            $schoolList[getUUID($loc, 'get')] = $loc['name'];
        }
        $this->set('schoolList',$schoolList);

        $subjectList = [];
        foreach($data['subjects'] as $subject){
            $subjectList[] = (object) ['id' => $subject['id'], 'uuid' => getUUID($subject, 'get'),'name' => sprintf('%s (%s)',$subject['name'],$subject['abbreviation'])];
        }
        $this->set('subjectList',$subjectList);

        $educationLevelList = [];
        foreach($data['educationLevels'] as $e){
            $educationLevelList[getUUID($e, 'get')] = $e['name'];
        }
        $this->set('educationLevelList',$educationLevelList);
        $this->set('educationLevels',$data['educationLevels']);

        $testKindList = [];
        foreach($data['testKinds'] as $e){
            $testKindList[getUUID($e, 'get')] = $e['name'];
        }
        $this->set('testKindList',$testKindList);

        $periodList = [];
        foreach($data['periods'] as $e){
            $periodList[getUUID($e, 'get')] = sprintf('%s (schooljaar %d)',$e['name'],$e['school_year']['year']);
        }
        $this->set('periodList',$periodList);

        $teacherList = [];
        foreach($data['teachers'] as $t){
            $teacherList[getUUID($t, 'get')] = $t;
        }
        $this->set('teachers',$data['teachers']);
        $this->set('teacherList',$teacherList);

    }

    public function import() {
        $this->isAuthorizedAs(['Administrator', 'Account manager', 'School manager', 'School management']);

        $data = $this->request->data['Qti'];

        if(!$data['file']['tmp_name']){
            $response = 'File niet gevonden om te importeren, probeer het nogmaals';
        }else{
            $r = $this->QtiImportService->uploadData($data);

            if(array_key_exists('error',$r)){
                $response = $r['error'];
            }
            else{
                $response = $r['data'];
            }
        }
        echo "
            <div id='response'>".$response."</div>
            <script>
                window.parent.handleQtiImportResponse(document.getElementById('response').outerHTML);
            </script>
        ";

        exit;


    }
}