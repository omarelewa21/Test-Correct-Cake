<?php

App::uses('AppController', 'Controller');
App::uses('AttainmentsService', 'Lib/Services');

class AttainmentsCitoController extends AppController
{
    public function beforeFilter()
    {
        $this->AttainmentsService = new AttainmentsService();

        parent::beforeFilter();
    }

    public function index()
    {
        $this->isAuthorizedAs(['Administrator', 'Account manager', 'School manager', 'School management']);

        $data = $this->AttainmentsService->getData();

        $subjects = [];
        foreach($data['subjects'] as $subject){
            $subjects[$subject['id']] = $subject['name'];
        }

        $view = 'import';
        $this->set('subjects',$subjects);
        $this->render($view);
    }

    public function import() {
        $this->isAuthorizedAs(['Administrator', 'Account manager', 'School manager', 'School management']);

        $data = $this->request->data['Attainments'];

        if(!$data['file']['tmp_name']){
            $response = 'File niet gevonden om te importeren, probeer het nogmaals';
        }else{
            $r = $this->AttainmentsService->uploadCitoData($data);

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