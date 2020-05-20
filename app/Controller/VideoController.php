<?php

App::uses('AppController', 'Controller');
App::uses('UsersService', 'Lib/Services');
App::uses('TestsService', 'Lib/Services');
App::uses('SchoolClassesService', 'Lib/Services');
App::uses('SchoolLocationsService', 'Lib/Services');
App::uses('TestTakesService', 'Lib/Services');
App::uses('MessagesService', 'Lib/Services');

class VideoController extends AppController
{
    public function beforeFilter()
    {
        $this->UsersService = new UsersService();

        parent::beforeFilter();
    }

    public function popup() {
        $this->set('url', $this->request->query('url'));
    }
}