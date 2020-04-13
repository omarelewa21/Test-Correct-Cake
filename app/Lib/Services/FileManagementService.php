<?php

App::uses('BaseService', 'Lib/Services');

/**
 * Class UsersService
 *
 *
 */
class FileManagementService extends BaseService {

    public function update($id,$params){
        $response = $this->Connector->putRequest('/filemanagement/'.$id,[], $params);
        if($response === false){
            return $this->Connector->getLastResponse();
        }

        return $response;
    }

    public function updateStatus($school_location_id,$id,$params = []){
        $response = $this->Connector->putRequest('/filemanagement/'.$school_location_id.'/'.$id,[], $params);
        if($response === false){
            return $this->Connector->getLastResponse();
        }

        return $response;
    }

    public function getDownload($id){
        $response = $this->Connector->getDownloadRequest('/filemanagement/'.$id.'/download', []);

        if($response === false){
            return $this->Connector->getLastResponse();
        }

        return $response;
    }

    public function getItem($id, $params){
        $r = $this->Connector->getRequest('/filemanagement/'.$id, $params);

        if(!$r){
            $r = json_decode($this->Connector->getLastResponse(),true);
        }
        return $r;
    }

    public function getData($params = []) {
        $response = $this->Connector->getRequest('/filemanagement', $params);
        if($response === false){
            return $this->Connector->getLastResponse();
        }

        return $response;
    }

    public function uploadTest($school_location_id,$data)
    {

        foreach($data['file'] as $key => $file){
            $data["files[$key]"] = new CURLFILE($file['tmp_name'],$file['type'],$file['name']);
        }
        unset($data['file']);
        $r = $this->Connector->postRequestFile('/filemanagement/'.$school_location_id.'/test', [], $data);

        if(!$r){
            $r = json_decode($this->Connector->getLastResponse(),true);
        }
        return $r;
    }

    public function uploadClass($school_location_id,$data)
    {
        $file = new CURLFILE($data['file']['tmp_name'],$data['file']['type'],$data['file']['name']);
        unset($data['file']);
        $data['file'] = $file;
        $r = $this->Connector->postRequestFile('/filemanagement/'.$school_location_id.'/class', [], $data);

        if(!$r){
            $r = json_decode($this->Connector->getLastResponse(),true);
        }
        return $r;
    }
}