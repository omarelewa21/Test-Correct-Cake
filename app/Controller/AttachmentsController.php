<?php

App::uses('AppController', 'Controller');
App::uses('AttachmentsService', 'Lib/Services');

class AttachmentsController extends AppController {

    public function beforeFilter()
    {
        $this->AttachmentsService = new AttachmentsService();

        parent::beforeFilter();
    }

    public function index() {

    }

}