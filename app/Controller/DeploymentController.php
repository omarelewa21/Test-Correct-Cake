<?php

App::uses('AppController', 'Controller');
App::uses('DeploymentService', 'Lib/Services');

/**
 * Demo controller
 *
 * @property DemoService $DemoService
 */
class DeploymentController extends AppController {

    /**
     * This is called before each action is parsed.
     *
     * @return void
     */
    public function beforeFilter()
    {
        $this->DeploymentService = new DeploymentService();

        parent::beforeFilter();
    }

    public function edit($uuid = null)
    {
        if(null === $uuid){
            die;
        }

        if($this->request->is('post') || $this->request->is('put')) {
            $data = $this->request->data['Deployment'];

            if(!$this->DeploymentService->update($uuid, $data)){
                $this->formResponse(false, $this->DeploymentService->getErrors());
                die;
            }

            $this->formResponse(true, []);
            die;
        }
        $deployment = [
            'Deployment' => $this->DeploymentService->show($uuid)
        ];
        $this->set('deployment',$deployment);
        $this->set('statuses', $this->DeploymentService->getStatuses());
        $this->request->data = $deployment;
    }

    public function add($uuid = null)
    {
        if($this->request->is('post') || $this->request->is('put')) {
            $data = $this->request->data['Deployment'];

            if(!$this->DeploymentService->create($data)){
                $this->formResponse(false, $this->DeploymentService->getErrors());
                die;
            }

            $this->formResponse(true, []);
            die;
        }

        $deployment = null;

        if(null !== $uuid){

            $d = $this->DeploymentService->show($uuid);
            $d['deployment_day'] = '';
            $d['status'] = 'PLANNED';
            $deployment['Deployment'] = $d;
            $this->set('deployment',$deployment);
            $this->request->data = $deployment;
        }
        $this->set('statuses', $this->DeploymentService->getStatuses());

    }

    public function index()
    {
        $this->set('deployments', $this->DeploymentService->index());
    }


}