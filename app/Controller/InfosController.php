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

            $this->dateFormatCheck($this->request->data['Info']['show_from'],'InfoShowFrom');
            $this->dateFormatCheck($this->request->data['Info']['show_until'],'InfoShowUntil');

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
        $this->setOptions();
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

    protected function setOptions()
    {
        $this->set('statuses', $this->InfoService->getStatuses());
        $this->set('types', $this->InfoService->getTypes());
        $rolesAr = $this->UsersService->getAllRoles();
        $roles = [];
        foreach($rolesAr as $role){
            $roles[$role['id']] = $role['name'];
        }
        $this->set('roles', $roles);
    }

    private function dateFormatCheck($stringDate,$inputName){
        if( date_create_from_format('Y-m-d H:i',$stringDate) === false){
            $this->formResponse(false,
                [
                    'type'=>'bad_format',
                    'input'=>$inputName
                ]);
            die;
        }
    }

    public function add($uuid = null)
    {
        if($this->request->is('post') || $this->request->is('put')) {
            $data = $this->request->data['Info'];

            $this->dateFormatCheck($this->request->data['Info']['show_from'],'InfoShowFrom');
            $this->dateFormatCheck($this->request->data['Info']['show_until'],'InfoShowUntil');

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
            $d['type'] = 'BASE';
            $info['Info'] = $d;
            $info['Info']['roles'] = $this->alterRolesForEdit($info['Info']['roles']);
            $this->set('info',$info);
            $this->request->data = $info;
        }
        $this->setOptions();
    }

    public function index()
    {
        $this->set('infos', $this->InfoService->index());
        $this->set('statuses', $this->InfoService->getStatuses());
    }

    public function dashboard()
    {
        $this->set('infos', $this->InfoService->dashboard());
    }

    public function removeDashboardInfo($uuid){
        if(!$this->InfoService->removeDashboardInfo($uuid)){
            $this->formResponse(false, $this->InfoService->getErrors());
            die;
        }

        $this->formResponse(true, []);

    }
}
