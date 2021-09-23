<?php

App::uses('AppController', 'Controller');
App::uses('UsersService', 'Lib/Services');
App::uses('TestsService', 'Lib/Services');
App::uses('SchoolClassesService', 'Lib/Services');
App::uses('SchoolLocationsService', 'Lib/Services');
App::uses('TestTakesService', 'Lib/Services');
App::uses('MessagesService', 'Lib/Services');
App::uses('SupportService', 'Lib/Services');

class SupportController extends AppController
{
    public function beforeFilter()
    {
        $this->UsersService = new UsersService();
        $this->SupportService = new SupportService();

        parent::beforeFilter();
    }

    public function index() {
        $this->isAuthorizedAs(['Administrator']);
    }

    public function load()
    {
        $this->isAuthorizedAs(['Administrator']);

        $logs = $this->SupportService->getTakeOverLogs($this->request->data);

        $this->set('logs', $logs);
    }
}