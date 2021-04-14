<?php

App::uses('AppController', 'Controller');
App::uses('WhitelistIpService', 'Lib/Services');

/**
 * Demo controller
 *
 * @property DemoService $DemoService
 */
class WhitelistIpController extends AppController {

    /**
     * This is called before each action is parsed.
     *
     * @return void
     */
    public function beforeFilter()
    {
        $this->Service = new WhitelistIpService();

        parent::beforeFilter();
    }

    public function delete($uuid) {
        if($this->request->is('post') || $this->request->is('put')) {
            $this->Service->delete($uuid);
            $this->formResponse(true, []);
            die;
        }
        die();
    }

    public function edit($uuid = null)
    {
        if(null === $uuid){
            die;
        }

        if($this->request->is('post') || $this->request->is('put')) {
            $data = $this->request->data['WhitelistIp'];

            $this->Service->update($uuid, $data);

            $this->formResponse(true, []);
            die;
        }
        $ip = [
            'WhitelistIp' => $this->Service->show($uuid)
        ];
        $this->set('whitelistIp',$ip);
        $this->request->data = $ip;
    }

    public function add()
    {
        if($this->request->is('post') || $this->request->is('put')) {
            $data = $this->request->data['WhitelistIp'];

            $this->Service->create($data);

            $this->formResponse(true, []);
            die;
        }
    }
}