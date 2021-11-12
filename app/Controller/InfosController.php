<?php

App::uses('AppController', 'Controller');
App::uses('InfoService', 'Lib/Services');
App::uses('UsersService', 'Lib/Services');

/**
 * Demo controller
 *
 * @property DemoService $DemoService
 */
class InfosController extends AppController {

    /**
     * This is called before each action is parsed.
     *
     * @return void
     */
    public function beforeFilter()
    {
        $this->InfoService = new InfoService();

        parent::beforeFilter();
    }

    public function edit($uuid = null)
    {
        if(null === $uuid){
            die;
        }

        if($this->request->is('post') || $this->request->is('put')) {
            $data = $this->request->data['Info'];

            if(!$this->InfoService->update($uuid, $data)){
                $this->formResponse(false, $this->InfoService->getErrors());
                die;
            }

            $this->formResponse(true, []);
            die;
        }

        $info = [
            'Info' => $this->InfoService->show($uuid)
        ];
        $info['Info']['roles'] = $this->alterRolesForEdit($info['Info']['roles']);
        $this->set('info',$info);
        $this->setStatusesAndRoles();
        $this->request->data = $info;
    }

    protected function alterRolesForEdit($roles)
    {
        $ids = [];
        foreach($roles as $role){
            $ids[] = $role['id'];
        }
        return $ids;
    }

    protected function setStatusesAndRoles()
    {
        $this->set('statuses', $this->InfoService->getStatuses());
        $rolesAr = $this->UsersService->getAllRoles();
        $roles = [];
        foreach($rolesAr as $role){
            $roles[$role['id']] = $role['name'];
        }
        $this->set('roles', $roles);
    }

    public function add($uuid = null)
    {
        if($this->request->is('post') || $this->request->is('put')) {
            $data = $this->request->data['Info'];

            if(!$this->InfoService->create($data)){
                $this->formResponse(false, $this->InfoService->getErrors());
                die;
            }

            $this->formResponse(true, []);
            die;
        }

        $info = null;

        if(null !== $uuid){

            $d = $this->InfoService->show($uuid);
            $d['show_from'] = '';
            $d['show_until'] = '';
            $d['status'] = 'INACTIVE';
            $info['Info'] = $d;
            $info['Info']['roles'] = $this->alterRolesForEdit($info['Info']['roles']);
            $this->set('info',$info);
            $this->request->data = $info;
        }
        $this->setStatusesAndRoles();
    }

    public function index()
    {
        $this->set('infos', $this->InfoService->index());
    }

    public function dashboard()
    {
        $this->set('infos', $this->InfoService->dashboard());
    }

}
