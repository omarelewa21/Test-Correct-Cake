<?php

App::uses('AppController', 'Controller');
App::uses('AttainmentsService', 'Lib/Services');

class AttainmentsController extends AppController
{
    public function beforeFilter()
    {
        $this->AttainmentsService = new AttainmentsService();

        parent::beforeFilter();
    }

    public function index()
    {
        $this->isAuthorizedAs(['Administrator', 'Account manager', 'School manager', 'School management']);

        $view = 'import';
        $this->render($view);
    }

    public function import() {
        $this->isAuthorizedAs(['Administrator', 'Account manager', 'School manager', 'School management']);

        $data = $this->request->data['Attainments'];

        if(!$data['file']['tmp_name']){
            $response = 'File niet gevonden om te importeren, probeer het nogmaals';
        }else{
            $r = $this->AttainmentsService->uploadData($data);

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
                window.parent.handleAttainmentsImportResponse(document.getElementById('response').outerHTML);
            </script>
        ";

        exit;


    }
}