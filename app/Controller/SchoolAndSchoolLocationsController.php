<?php

App::uses('AppController', 'Controller');
App::uses('SchoolAndSchoolLocationsService', 'Lib/Services');
App::uses('HelperFunctions','Lib');

/**
 * Sections controller
 *
 */
class SchoolAndSchoolLocationsController extends AppController
{
    public function beforeFilter()
    {
        $this->SchoolAndSchoolLocationsService = new SchoolAndSchoolLocationsService();
        parent::beforeFilter();
    }

    public function index()
    {
        $this->isAuthorizedAs(['Administrator']);
    }

    public function import() {
        $this->isAuthorizedAs(['Administrator']);

        $data = $this->request->data['SchoolAndSchoolLocations'];
        $nextFase = 'start';

        if(!$data['file']['tmp_name']){
            $response = __("File niet gevonden om te importeren, probeer het nogmaals");
            $nextFase = 'restartStartWithError';
        }else{
            $r = $this->SchoolAndSchoolLocationsService->uploadData($data);

            if(array_key_exists('errors',$r)){
                $errors = [];
                foreach($r['errors'] as $item){
                    if(is_array($item)){
                        foreach($item as $e){
                            $errors[] = $e;
                        }
                    } else {
                        $errors[] = $item;
                    }
                }
                $response = implode('<br>',$errors);
                $nextFase = 'showSkipValidation';
            }
            else if(array_key_exists('error',$r)){
                $response = $r['error'];
                $nextFase = 'showSkipValidation';
            }
            else{
                $response = $r['data'];
                $nextFase = 'finish';
            }
        }

        $fatalErrorCheckText = 'FATAL ERROR';
        $hasFatalError = $fatalErrorCheckText !== '' && mb_strpos($response, $fatalErrorCheckText) !== false;


        echo "
            <div id='response'>".$response."</div>
            <script>
                window.parent.handleSchoolAndSchoolLocationsImportResponse(document.getElementById('response').outerHTML,'".$nextFase."', ".$hasFatalError.");
            </script>
        ";
        exit;
    }

}