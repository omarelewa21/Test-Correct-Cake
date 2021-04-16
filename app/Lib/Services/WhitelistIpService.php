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
        if(!$this->Connector->getRequest('/maintenance_whitelist_ip',[])){
            $this->addError($this->Connector->getLastResponse());
            return false;
        }
        return true;
    }

    public function show($uuid)
    {
        if(!$this->Connector->getRequest('/maintenance_whitelist_ip/'.$uuid,[])){
            $this->addError($this->Connector->getLastResponse());
            return false;
        }
        return true;
    }

    public function create($data)
    {
        if(!$this->Connector->postRequest('/maintenance_whitelist_ip',[],$data)){
            $this->addError($this->Connector->getLastResponse());
            return false;
        }
        return true;
    }

    public function update($uuid,$data)
    {
        if(!$this->Connector->putRequest('/maintenance_whitelist_ip/'.$uuid,[],$data)){
            $this->addError($this->Connector->getLastResponse());
            return false;
        }
        return true;
    }

    public function delete($uuid)
    {
        if(!$this->Connector->deleteRequest('/maintenance_whitelist_ip/'.$uuid,[])){
            $this->addError($this->Connector->getLastResponse());
            return false;
        }
        return true;
    }

}