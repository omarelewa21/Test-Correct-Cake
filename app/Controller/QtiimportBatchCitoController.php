<?php

App::uses('AppController', 'Controller');
App::uses('QtiImportBatchCitoService', 'Lib/Services');

class QtiimportBatchCitoController extends AppController
{
    public function beforeFilter()
    {
        $this->QtiImportService = new QtiImportBatchCitoService();

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
            $subjectList[] = (object) ['id' => getUUID($subject, 'get'),'name' => sprintf('%s (%s)',str_replace("'","`",preg_replace('!\\r?\\n?\\t!', "", $subject['name'])),$subject['abbreviation'])];
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
        for($i = 1; $i < count($testKindList)+1; $i++){
            $testKindList[$i] = __("$testKindList[$i]");
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

        ini_set('memory_limit','-1');

        $data = $this->request->data['Qti'];

        if(!$data['file']['tmp_name']){
            $response = __("File niet gevonden om te importeren, probeer het nogmaals");
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
