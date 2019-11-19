<?php

App::uses('BaseService', 'Lib/Services');

/**
 * Class UsersService
 *
 *
 */
class QtiImportService extends BaseService {

    public function getData($params = []) {
        $response = $this->Connector->getRequest('/qtiimport/data', $params);
        if($response === false){
            return $this->Connector->getLastResponse();
        }

        return $response;
    }

    public function uploadData($data)
    {
        $file = new CURLFILE($data['file']['tmp_name'],$data['file']['type'],$data['file']['name']);
        unset($data['file']);
        $data['zip_file'] = $file;
        $r = $this->Connector->postRequestFile('/qtiimport/import', [], $data);

        if(!$r){
            $r = json_decode($this->Connector->getLastResponse(),true);
        }
        return $r;
    }
}