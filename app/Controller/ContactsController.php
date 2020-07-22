<?php

App::uses('AppController', 'Controller');
App::uses('ContactsService', 'Lib/Services');

class ContactsController extends AppController
{

    public function beforeFilter()
    {
        $this->ContactsService = new ContactsService();

        parent::beforeFilter();
    }

    public function add($owner, $owner_id) {
        $this->isAuthorizedAs(['Administrator', 'Account manager', 'School manager', 'School management']);

        if($this->request->is('post') || $this->request->is('put')) {

            $data = $this->request->data['Contact'];

            $result = $this->ContactsService->addContact($data);

            $this->ContactsService->connectTo($result, $owner, $owner_id, $data['type']);

            $this->formResponse(
                $result ? true : false,
                []
            );
            die;
        }
    }

    public function delete($owner, $owner_id, $type, $contact_id) {
        $this->isAuthorizedAs(['Administrator', 'Account manager', 'School manager', 'School management']);

        if($this->request->is('delete')) {
            $this->autoRender = false;
            $this->ContactsService->deleteContact($owner, $owner_id, $type, $contact_id);
        }
    }

    public function edit($contact_id) {

        $this->isAuthorizedAs(['Administrator', 'Account manager', 'School manager', 'School management']);

        if($this->request->is('post') || $this->request->is('put')) {

            $data = $this->request->data['Contact'];

            $response = $this->ContactsService->updateContact($contact_id, $data);

            $this->formResponse(
                $response ? true : false,
                []
            );
            die;
        }

        $contact = $this->ContactsService->getContact($contact_id);
        $contact['Contact'] = $contact;
        $this->request->data = $contact;
    }
}