<?php

App::uses('BaseService', 'Lib/Services');

/**
 * Class DemoService
 *
 *
 */
class WhitelistIpService extends BaseService {

    public function index()
    {
        return $this->Connector->getRequest('/maintenance_whitelist_ip',[]);
    }

    public function show($uuid)
    {
        return $this->Connector->getRequest('/maintenance_whitelist_ip/'.$uuid,[]);
    }

    public function create($data)
    {
        return $this->Connector->postRequest('/maintenance_whitelist_ip',[],$data);
    }

    public function update($uuid,$data)
    {
        return $this->Connector->putRequest('/maintenance_whitelist_ip/'.$uuid,[],$data);
    }

    public function delete($uuid)
    {
        return $this->Connector->deleteRequest('/maintenance_whitelist_ip/'.$uuid,[]);
    }

}