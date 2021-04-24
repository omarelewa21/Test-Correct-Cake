<?php

App::uses('BaseService', 'Lib/Services');

/**
 * Class DemoService
 *
 *
 */
class DeploymentService extends BaseService {

    public function index()
    {
        return $this->Connector->getRequest('/deployment',[]);
    }

    public function show($uuid)
    {
        return $this->Connector->getRequest('/deployment/'.$uuid,[]);
    }

    public function create($data)
    {
        if(!$this->Connector->postRequest('/deployment',[],$data)){
            $this->addError($this->Connector->getLastResponse());
            return false;
        }
        return true;
    }

    public function update($uuid,$data)
    {
        if(!$this->Connector->putRequest('/deployment/'.$uuid,[],$data)){
            $this->addError($this->Connector->getLastResponse());
            return false;
        }
        return true;
    }

    public function getStatuses()
    {
        return [
            'PLANNED' => 'Gepland',
            'NOTIFY' => 'Vooraankondiging tonen',
            'ACTIVE' => 'Deployment actief',
            'DONE' => 'Deployment afgerond',
        ];
    }
}