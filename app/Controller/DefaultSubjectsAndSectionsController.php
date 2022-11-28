<?php

App::uses('AppController', 'Controller');
App::uses('DefaultSubjectsAndSectionsService', 'Lib/Services');
App::uses('HelperFunctions','Lib');

/**
 * Sections controller
 *
 */
class DefaultSubjectsAndSectionsController extends AppController
{
    public function beforeFilter()
    {
        $this->DefaultSubjectsAndSectionsService = new DefaultSubjectsAndSectionsService();
        parent::beforeFilter();
    }

    public function index()
    {
        $this->isAuthorizedAs(['Administrator']);
    }

    public function import() {
        $this->isAuthorizedAs(['Administrator']);

        $data = $this->request->data['DefaultSubjectsAndSections'];

        if(!$data['file']['tmp_name']){
            $response = __("File niet gevonden om te importeren, probeer het nogmaals");
        }else{
            $r = $this->DefaultSubjectsAndSectionsService->uploadData($data);

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
            }
            else if(array_key_exists('error',$r)){
                $response = $r['error'];
            }
            else{
                $response = $r['data'];
            }
        }
        echo "
            <div id='response'>".$response."</div>
            <script>
                window.parent.handleDefaultSubjectsAndSectionsImportResponse(document.getElementById('response').outerHTML);
            </script>
        ";
        exit;
    }

}