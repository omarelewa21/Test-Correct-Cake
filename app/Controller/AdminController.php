<?php

App::uses('AppController', 'Controller');
App::uses('UsersService', 'Lib/Services');
App::uses('TestsService', 'Lib/Services');
App::uses('SchoolClassesService', 'Lib/Services');
App::uses('SchoolLocationsService', 'Lib/Services');
App::uses('TestTakesService', 'Lib/Services');
App::uses('MessagesService', 'Lib/Services');

class AdminController extends AppController
{
    public function beforeFilter()
    {
        $this->UsersService = new UsersService();

        parent::beforeFilter();
    }

    public function teacher_stats() {
        $this->isAuthorizedAs(['Administrator', 'Account manager', 'School manager', 'School management']);

        $data = $this->UsersService->getAdminTeacherStats();

        $this->set('data', (object) $data);
    }
}