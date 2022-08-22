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
        $fatalErrorCheckText = 'FATAL ERROR';

        if(!$data['file']['tmp_name']){
            $response = __("File niet gevonden om te importeren, probeer het nogmaals");
            $nextFase = 'restartStartWithError';
        }else{
            $r = $this->SchoolAndSchoolLocationsService->uploadData($data);

            if(array_key_exists('errors',$r)) {
                $r['error'] = $r['errors'];
            }
            if(array_key_exists('error',$r)){
                $response = $r['error'];
                $nextFase = 'showSkipValidation';

                if(HelperFunctions::getInstance()->isJson($response)) {
                    $response = json_decode($response);
                } else if(is_string($response)){
                    $response = [$response];
                }

                $data = '';
                if(is_array($response)){
                    foreach($response as $error) {
                        $color = ($fatalErrorCheckText !== '' && mb_strpos($error, $fatalErrorCheckText) !== false) ? 'red' : '';
                        $data .= sprintf('<li style="color:%s">%s</li>',$color, $error);
                    }
                    $response = sprintf('<ul>%s</ul>',$data);
                }
            }
            else{
                $response = $r['data'];
                $nextFase = 'finish';
            }
        }


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