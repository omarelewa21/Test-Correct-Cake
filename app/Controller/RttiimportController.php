<?php

App::uses('AppController', 'Controller');
App::uses('RttiImportService', 'Lib/Services');

class RttiimportController extends AppController {

    public function beforeFilter() {
        try {
            $this->RttiImportService = new RttiImportService();
        } catch (\PHPUnit\Framework\Exception $e) {
            var_dump($e);
        }

        parent::beforeFilter();
    }

    public function index() {
        
        $this->isAuthorizedAs(['Administrator', 'Account manager', 'School manager', 'School management']);
        $data = $this->RttiImportService->getData();
        
    }

    public function import() {

        $this->isAuthorizedAs(['Administrator', 'Account manager', 'School manager', 'School management']);     
        
        $data = $this->request->data['Rtti'];

        if (!$data['file']['tmp_name']) {

            $response = __("File niet gevonden om te importeren, probeer het nogmaals");
            
        } else {  
            
            $r = $this->RttiImportService->uploadData($data);
            
            if (array_key_exists('error', $r)) {
                $response = $r['error'];
            } else {
                $response = $r['data']['data'];
            }
            
        }

        echo "
            <div id='response'>" . $response . "</div>
            <script>
                window.parent.handleRttiImportResponse(document.getElementById('response').outerHTML);
            </script>
        ";

        exit;
    }

    public function validateUpload($data) {
        
    }

}
