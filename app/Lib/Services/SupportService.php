<?php

App::uses('BaseService', 'Lib/Services');

class SupportService extends BaseService {

    public function registerTakeOverForUser($user_uuid, $params = [])
    {
        $response = $this->Connector->putRequest('/support/register_take_over/'.$user_uuid, $params, []);
        if ($response === false) {
            return $this->Connector->getLastResponse();
        }
        return $response;
    }

    public function getTakeOverLogs($params = [])
    {
        $response = $this->Connector->getRequest('/support/index', $params);
        if ($response === false) {
            return $this->Connector->getLastResponse();
        }
        return $response;
    }

    public function getTakeOverLogsForUser($user_uuid)
    {
        $response = $this->Connector->getRequest('/support/show/'.$user_uuid, []);
        if ($response === false) {
            return $this->Connector->getLastResponse();
        }
        return $response;
    }
}