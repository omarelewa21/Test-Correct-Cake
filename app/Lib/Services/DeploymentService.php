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
        return $this->Connector->postRequest('/deployment',[],$data);
    }

    public function update($uuid,$data)
    {
        return $this->Connector->putRequest('/deployment/'.$uuid,[],$data);
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