<?php

App::uses('BaseService', 'Lib/Services');

/**
 * Class DemoService
 *
 *
 */
class BugsnagService extends BaseService {



    public function sendMessage($data)
    {
        if (!$this->Connector->postRequest('/bugsnag_error', [], $data)) {
            $this->addError($this->Connector->getLastResponse());
            return false;
        }

        return true;
    }



}