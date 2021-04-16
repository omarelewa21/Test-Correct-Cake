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
        if(!$this->Connector->getRequest('/deployment',[])){
            $this->addError($this->Connector->getLastResponse());
            return false;
        }
        return true;
    }

    public function show($uuid)
    {
        if(!$this->Connector->getRequest('/deployment/'.$uuid,[])){
            $this->addError($this->Connector->getLastResponse());
            return false;
        }
        return true;
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