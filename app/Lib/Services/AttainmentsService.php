<?php

App::uses('BaseService', 'Lib/Services');

/**
 * Class UsersService
 *
 *
 */
class AttainmentsService extends BaseService {

    public function getData($params = []) {
        $response = $this->Connector->getRequest('/attainments/data', $params);
        if($response === false){
            return $this->Connector->getLastResponse();
        }

        return $response;
    }

    public function uploadData($data)
    {
        $file = new CURLFILE($data['file']['tmp_name'],$data['file']['type'],$data['file']['name']);
        unset($data['file']);
        $data['attainments'] = $file;
        $r = $this->Connector->postRequestFile('/attainments/import', [], $data);

        if(!$r){
            $r = json_decode($this->Connector->getLastResponse(),true);
        }
        return $r;
    }

    public function uploadCitoData($data)
    {
        $file = new CURLFILE($data['file']['tmp_name'],$data['file']['type'],$data['file']['name']);
        unset($data['file']);
        $data['attainments'] = $file;
        $r = $this->Connector->postRequestFile('/attainments_cito/import', [], $data);

        if(!$r){
            $r = json_decode($this->Connector->getLastResponse(),true);
        }
        return $r;
    }
}