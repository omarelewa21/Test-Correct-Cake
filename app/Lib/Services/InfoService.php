<?php

App::uses('BaseService', 'Lib/Services');

/**
 * Class DemoService
 *
 *
 */
class InfoService extends BaseService {

    public function dashboard()
    {
        return $this->index(['mode' => 'dashboard']);
    }

    public function index($params = ['mode' => 'index'])
    {
        return $this->Connector->getRequest('/info',$params);
    }

    public function features($params = ['mode' => 'feature'])
    {
        return $this->Connector->getRequest('/info',$params);
    }

    public function show($uuid)
    {
        return $this->Connector->getRequest('/info/'.$uuid,[]);
    }

    public function create($data)
    {
        if(!$this->Connector->postRequest('/info',[],$data)){
            $this->addError($this->Connector->getLastResponse());
            return false;
        }
        return true;
    }

    public function update($uuid,$data)
    {
        if(!$this->Connector->putRequest('/info/'.$uuid,[],$data)){
            $this->addError($this->Connector->getLastResponse());
            return false;
        }


        return true;
    }

    public function getStatuses()
    {
        return [
            'INACTIVE' => __('info.Niet zichtbaar'),
            'ACTIVE' => __('info.Zichtbaar'),
        ];
    }

    public function getTypes($params = ['mode' => 'types'])
    {
        return array_map(
            fn($value) => __($value),
            $this->Connector->getRequest('/info',$params)
        );
    }

    public function removeDashboardInfo($uuid)
    {
        if(!$this->Connector->postRequest('/info/removeDashboardInfo/' . $uuid, [], [])){
            $this->addError($this->Connector->getLastResponse());
            return false;
        }

        return true;        
    }

    public function seenNewFeatures()
    {
        $UserSettings = $this->Connector->putRequest('/info/seenNewFeatures/', [], []);

        if(!$UserSettings){
            $this->addError($this->Connector->getLastResponse());
            return false;
        }

        return $UserSettings;
    }

    public function closedNewFeaturesMessage()
    {
        $UserSettings = $this->Connector->putRequest('/info/closedNewFeaturesMessage/', [], []);

        if(!$UserSettings){
            $this->addError($this->Connector->getLastResponse());
            return false;
        }

        return $UserSettings;
    }
}
